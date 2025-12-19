<?php

declare(strict_types=1);

namespace api\modules\v1\controllers;

use api\enums\ResponseCode;
use api\enums\ResponseCodeMessage;
use api\models\Task;
use api\modules\Module;
use api\schemas\task\response\TaskResponseDto;
use api\schemas\task\TaskDto;
use api\services\TaskService;
use Throwable;
use Yii;
use yii\web\Response;
use OpenApi\Attributes as OA;

#[OA\Info(
  version: '1.0.0',
  title: 'Task API',
  description: 'API для управления задачами'
)]
#[OA\Server(url: '/api', description: 'API сервер')]
#[OA\Tag(name: 'Tasks', description: 'Управление задачами')]
class TaskController extends BaseApiController
{
  public function __construct(
    $id,
    $module,
    private readonly TaskService $taskService,
    $config = [],
  )
  {
    parent::__construct($id, $module, $config);
  }
  
  #[OA\Get(
    path: '/v1/tasks',
    summary: 'Получить список всех задач',
    tags: ['Tasks'],
    responses: [
      new OA\Response(
        response: ResponseCode::SUCCESS->value,
        description: 'Список задач получен',
        content: new OA\JsonContent(
          allOf: [
            new OA\Schema(ref: '#/components/schemas/ApiSuccessResponse'),
            new OA\Schema(
              properties: [
                new OA\Property(
                  property: 'data',
                  ref: '#/components/schemas/TasksResponse',
                ),
              ],
            ),
          ],
        ),
      ),
      new OA\Response(
        response: ResponseCode::SERVER_ERROR->value,
        description: ResponseCodeMessage::SERVER_ERROR->value,
        content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
      ),
    ]
  )]
  public function actionIndex(): Response
  {
    try {
      $tasks = $this->taskService->getAllTasks();
      
      return $this->asSuccess(
        data: $tasks,
        message: ResponseCodeMessage::SUCCESS->value
      );
    } catch (Throwable $e) {
      return $this->handleException($e, 'Получение списка задач');
    }
  }
  
  #[OA\Get(
    path: '/v1/tasks/{id}',
    summary: 'Получить задачу по ID',
    tags: ['Tasks'],
    parameters: [
      new OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
      )
    ],
    responses: [
      new OA\Response(
        response: ResponseCode::SUCCESS->value,
        description: 'Задача найдена',
        content: new OA\JsonContent(
          allOf: [
            new OA\Schema(ref: '#/components/schemas/ApiSuccessResponse'),
            new OA\Schema(
              properties: [
                new OA\Property(
                  property: 'data',
                  ref: '#/components/schemas/Task',
                ),
              ],
            ),
          ],
        ),
      ),
      new OA\Response(
        response: ResponseCode::NOT_FOUND->value,
        description: 'Задача не найдена',
        content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
      ),
    ]
  )]
  public function actionView(int $id): Response
  {
    if ($error = $this->validateId($id)) {
      return $error;
    }
    
    try {
      $task = $this->taskService->getTaskById($id);
      
      if ($task === null) {
        return $this->asError(
          message: 'Задача не найдена',
          code: ResponseCode::NOT_FOUND
        );
      }
      
      return $this->asSuccess(data: $task);
    } catch (Throwable $e) {
      return $this->handleException($e, 'Получение задачи');
    }
  }
  
  #[OA\Post(
    path: '/v1/tasks/create',
    summary: 'Создать новую задачу',
    tags: ['Tasks'],
    requestBody: new OA\RequestBody(
      required: true,
      content: new OA\JsonContent(
        required: ['title'],
        properties: [
          new OA\Property(property: 'title', type: 'string', example: 'Новая задача'),
          new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Описание задачи'),
        ]
      )
    ),
    responses: [
      new OA\Response(
        response: ResponseCode::CREATED->value,
        description: 'Задача создана',
        content: new OA\JsonContent(
          allOf: [
            new OA\Schema(ref: '#/components/schemas/ApiSuccessResponse'),
            new OA\Schema(
              properties: [
                new OA\Property(
                  property: 'data',
                  ref: '#/components/schemas/Task',
                ),
              ],
            ),
          ],
        ),
      ),
      new OA\Response(
        response: ResponseCode::BAD_REQUEST->value,
        description: 'Ошибка валидации',
        content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
      ),
      new OA\Response(
        response: ResponseCode::SERVER_ERROR->value,
        description: ResponseCodeMessage::SERVER_ERROR->value,
        content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
      ),
    ],
  )]
  public function actionCreate(): Response
  {
    try {
      $data = Yii::$app->request->post();
      $result = $this->taskService->createTask($data);
      
      if ($result instanceof Task && $result->hasErrors()) {
        return $this->asValidationError(
          message: ResponseCodeMessage::VALIDATION_ERROR->value,
          errors: $result->getErrors()
        );
      }
      
      return $this->asSuccess(
        data: $result,
        message: ResponseCodeMessage::CREATED->value
      );
    } catch (Throwable $e) {
      return $this->handleException($e, 'Создание задачи');
    }
  }
  
  #[OA\Put(
    path: '/v1/tasks/{id}',
    summary: 'Обновить задачу',
    tags: ['Tasks'],
    parameters: [
      new OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
      )
    ],
    requestBody: new OA\RequestBody(
      content: new OA\JsonContent(
        properties: [
          new OA\Property(property: 'title', type: 'string', example: 'Обновленная задача'),
          new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Новое описание'),
          new OA\Property(property: 'status', type: 'integer', enum: [10, 20, 30], example: 20),
        ]
      )
    ),
    responses: [
      new OA\Response(
        response: ResponseCode::SUCCESS->value,
        description: 'Задача обновлена',
        content: new OA\JsonContent(
          allOf: [
            new OA\Schema(ref: '#/components/schemas/ApiSuccessResponse'),
            new OA\Schema(
              properties: [
                new OA\Property(
                  property: 'data',
                  ref: '#/components/schemas/Task',
                ),
              ],
            ),
          ],
        ),
      ),
      new OA\Response(
        response: ResponseCode::NOT_FOUND->value,
        description: 'Задача не найдена',
        content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
      ),
      new OA\Response(
        response: ResponseCode::BAD_REQUEST->value,
        description: 'Ошибка валидации',
        content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
      ),
    ]
  )]
  public function actionUpdate(int $id): Response
  {
    if ($error = $this->validateId($id)) {
      return $error;
    }
    
    try {
      $data = Yii::$app->request->post();
      $result = $this->taskService->updateTask($id, $data);
      
      if ($result === null) {
        return $this->asError(
          message: 'Задача не найдена',
          code: ResponseCode::NOT_FOUND
        );
      }
      
      if ($result instanceof Task && $result->hasErrors()) {
        return $this->asValidationError(
          message: ResponseCodeMessage::VALIDATION_ERROR->value,
          errors: $result->getErrors()
        );
      }
      
      return $this->asSuccess(data: $result);
    } catch (Throwable $e) {
      return $this->handleException($e, 'Обновление задачи');
    }
  }
  
  #[OA\Delete(
    path: '/v1/tasks/{id}',
    summary: 'Удалить задачу',
    tags: ['Tasks'],
    parameters: [
      new OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
      )
    ],
    responses: [
      new OA\Response(
        response: ResponseCode::NO_CONTENT->value,
        description: 'Задача удалена'
      ),
      new OA\Response(
        response: ResponseCode::NOT_FOUND->value,
        description: 'Задача не найдена',
        content: new OA\JsonContent(ref: '#/components/schemas/ApiErrorResponse'),
      ),
    ]
  )]
  public function actionDelete(int $id): Response
  {
    if ($error = $this->validateId($id)) {
      return $error;
    }
    
    try {
      $deleted = $this->taskService->deleteTask($id);
      
      if (!$deleted) {
        return $this->asError(
          message: 'Задача не найдена',
          code: ResponseCode::NOT_FOUND,
        );
      }
      
      return $this->asSuccess(
        message: 'Задача удалена',
      );
    } catch (Throwable $e) {
      return $this->handleException($e, 'Удаление задачи');
    }
  }
}
