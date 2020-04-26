<?php

namespace App\Http\Controllers;

use App\Lib\Constants\ErrorCodes;
use App\Lib\Constants\HttpStatusCodes;
use App\Lib\EntityProcessor\EntityProcessor;
use App\Lib\FiltersHelper;
use App\Lib\RequestContext;
use App\Lib\RequestHelper;
use App\Lib\Response\ResponseBuilder;
use App\Lib\ValidatorHelper;
use App\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Throwable;

class BaseController extends RootController
{
    /**
     * The context of the current
     * request.
     *
     * @var App\Lib\RequestContext
     */
    protected $context;

    /**
     * The fully qualified class name
     * of the main model associated to
     * this controller.
     *
     * @var string
     */
    protected $model;

    /**
     * The ValidatorHelper instance
     * used to format the validation
     * errors.
     *
     * @var App\Lib\ValidatorHelper
     */
    protected $validatorHelper;

    /**
     * The FiltersHelper instance
     * used to check the model
     * properties.
     *
     * @var App\Lib\FiltersHelper
     */
    protected $filtersHelper;

    /**
     * The default set of related entities
     * to be included in the response of index()
     * requests is not explicitly specified
     * through the 'with' attribute.
     *
     * This attribute must be a comma-separated
     * list of related entities.
     *
     * @var string
     */
    protected $defaultWithOnIndex = 'all';

    /**
     * The default set of related entities
     * to be included in the response of show()
     * requests is not explicitly specified
     * through the 'with' attribute.
     *
     * This attribute must be a comma-separated
     * list of related entities.
     *
     * @var string
     */
    protected $defaultWithOnShow = 'all';

    /**
     * The RequestHelper instance to
     * be used when parsing the input.
     *
     * @var App\Lib\RequestHelper
     */
    protected $requestHelper;

    /**
     * The ResponseBuilder instance to
     * be used when generating responses.
     *
     * @var App\Lib\Response
     */
    protected $responseBuilder;

    /**
     * The model name related to the controller.
     *
     * @var App\Models\BaseModel
     */
    protected $modelName = BaseModel::class;

    /**
     * The entity processor instance.
     *
     * @var App\Lib\EntityProcessor\EntityProcessor
     */
    protected $entityProcessor;

    /**
     * Constructs an instance of the class.
     * Instantiates the accesor based on it's model,
     * also intanstiates the request, the response and the
     * response builder.
     *
     * The context is also instantiated based on what
     * is recieved in the request.
     *
     * @return void
     */
    public function __construct()
    {
        $this->responseBuilder = app()->make(ResponseBuilder::class);
        $this->context = app()->make(RequestContext::class);
        $this->requestHelper = app()->make(RequestHelper::class);
        $this->validatorHelper = app()->make(ValidatorHelper::class);
        $this->filtersHelper = app()->make(FiltersHelper::class);
        $this->entityProcessor = app()->make(EntityProcessor::class);
        $this->model = app()->make($this->modelName);

        $this->initializeRequestContext();
    }

    /**
     * Parses the with param from the query data
     * to return an array.
     *
     * @param  array|string $with
     *
     * @return array
     */
    private function parseWithParamFromQueryData($with)
    {
        if (!is_array($with)) {
            $with = array_map(function ($element) {
                return Str::camel($element);
            }, explode(',', $with));
        }

        return $with;
    }

    /**
     * Parses the withCount param from the query data
     * to return an array.
     *
     * @param  array|string $withCount
     *
     * @return array
     */
    private function parseWithCountParamFromQueryData($withCount)
    {
        if (!is_array($withCount)) {
            $withCount = array_map(function ($element) {
                return Str::camel($element);
            }, explode(',', $withCount));
        }

        return $withCount;
    }

    /**
     * Asks the model for a set of entities based on the
     * context and returns them as a json http response.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $entities = $this->model->getPaginatedObjects($this->context);

        return response()->json($entities);
    }

    /**
     * Asks the model for a single entitiy based on the
     * context and returns it as a json http response.
     * Or an http error if the entity id does not match any.
     *
     * @param int $id
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function show($entityId)
    {
        $entity = $this->model->getObject($entityId, $this->context);
        if (!$entity) {
            return $this->responseBuilder->buildErrorResponse(
                $this->entityProcessor->getModelErrorCode($this->model, 'notFound')
            );
        }
        if (Gate::denies('view', $entity)) {
            return $this->responseBuilder->buildErrorResponse(ErrorCodes::USER_NOT_AUTHORIZED, null);
        }

        return response()->json($entity);
    }

    /**
     * Fetch the new entity parameters from the request
     * validates them and checks if the model is not readOnly,
     * if everything is good asks for the model to store it and
     * then returns the new stored entity as an json http response.
     * If not an appropriate http json error will be returned.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $result = $this->entityProcessor->store(
            $this->requestHelper->requestData(),
            $this->modelName,
            $this->context
        );
        if ($result['code'] === null) {
            return response()->json($result['data'], HttpStatusCodes::CREATED);
        }

        return $this->responseBuilder->buildErrorResponse($result['code'], $result['data']);
    }

    /**
     * Updates an entity by its id with the parameters from the request
     * checks for its existance, the readOnly and validates them.
     * If everything is good asks for the model to updated it and
     * then returns the new updated entity as an json http response.
     * If not an appropriate http json error will be returned.
     *
     * @param int $id
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function update($entityId)
    {
        $result = $this->entityProcessor->update(
            $entityId,
            $this->requestHelper->requestData(),
            $this->modelName,
            $this->context
        );
        if ($result['code'] === null) {
            return response()->json($result['data']);
        }

        return $this->responseBuilder->buildErrorResponse($result['code'], $result['data']);
    }

    /**
     * Removes an entity by its id. First checks for its existance
     * and the readOnly.
     * If everything is good asks for the model to delete it and
     * then returns an empty json http response.
     * If not an appropriate http json error will be returned.
     *
     * @param int $id
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy($entityId)
    {
        $entity = $this->model->getObject($entityId, $this->context);
        if (Gate::denies('delete', $this->model)) {
            return $this->responseBuilder->buildErrorResponse(ErrorCodes::USER_NOT_AUTHORIZED);
        }
        if (!$entity) {
            return $this->responseBuilder->buildErrorResponse(
                $this->entityProcessor->getModelErrorCode(
                    $this->model,
                    'notFound'
                )
            );
        }

        try {
            DB::beginTransaction();
            /*
             * Hook: beforeDestroy
             */
            if ($filterResponse = $this->model->beforeDestroy($entity)) {
                return $filterResponse;
            }

            $entity->delete();

            DB::commit();

            return response()->json(null, HttpStatusCodes::NO_CONTENT);
        } catch (Throwable $e) {
            DB::rollBack();
            \Log::error('Error when trying to destroy an entity. Message: ' . $e->getMessage());

            return $this->responseBuilder->buildErrorResponse(
                $this->entityProcessor->getModelErrorCode($this->model, 'failedToDelete')
            );
        }
    }

    /**
     * Initializes the request context.
     *
     * @return null
     */
    private function initializeRequestContext()
    {
        $queryData = $this->requestHelper->queryData();
        $this->context->setRequestContext(
            $queryData['page'],
            $queryData['pageSize'],
            $queryData['sortField'],
            $queryData['sortDirection'],
            $this->parseWithParamFromQueryData($queryData['with']),
            $this->parseWithCountParamFromQueryData($queryData['withCount']),
            $this->filtersHelper->getFilters(
                $this->requestHelper->getFilters(),
                $this->model->getFilters()
            )
        );
    }
}
