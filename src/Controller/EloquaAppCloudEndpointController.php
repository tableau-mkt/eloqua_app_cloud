<?php

namespace Drupal\eloqua_app_cloud\Controller;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Queue\QueueFactory;
use Drupal\Core\Queue\QueueInterface;
use Drupal\eloqua_rest_api\Factory\ClientFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;


/**
 * Class EndpointControllerBase.
 *
 * @property  pluginManagerService
 * @package Drupal\eloqua_app_cloud\Controller
 */
class EloquaAppCloudEndpointController extends ControllerBase {


  /**
   * @var string
   *
   */
  protected $eloquaInstanceId;

  /**
   * @var \Eloqua\Client
   */
  protected $eloqua;

  /**
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * @var LanguageManagerInterface
   */
  protected $langManager;

  /**
   * @var EntityTypeManager
   */
  protected $entityManager;

  /**
   * @var QueueFactory
   */
  protected $queueFactory;

  /**
   * @var array $plugins
   */
  protected $plugins;


  /**
   * EndpointControllerBase constructor.
   *
   * @param \Drupal\eloqua_app_cloud\Controller\ClientFactory|\Drupal\eloqua_rest_api\Factory\ClientFactory $eloquaFactory
   * @param \Drupal\eloqua_app_cloud\Controller\RequestStack|\Symfony\Component\HttpFoundation\RequestStack $requestStack
   * @param \Drupal\Core\Language\LanguageManagerInterface $langManager
   * @param \Drupal\Core\Entity\EntityTypeManager $entityManager
   * @param \Drupal\Core\Queue\QueueFactory $queue
   * @param $plugins
   */
  public function __construct(ClientFactory $eloquaFactory, RequestStack $requestStack, LanguageManagerInterface $langManager, EntityTypeManager $entityManager, QueueFactory $queueFactory,array $plugins) {
    $this->eloqua = $eloquaFactory->get();
    $this->request = $requestStack->getCurrentRequest();
    $this->langManager = $langManager;
    $this->entityManager = $entityManager;
    $this->queueFactory = $queueFactory;
    $this->plugins = $plugins;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {

    // Get the list of plugin managers in our "namespace".
    foreach ($container->getServiceIds() as $serviceId) {
      if (strpos($serviceId, 'plugin.manager.eloqua_app_cloud') === 0) {
        $type = $container->get($serviceId);
        $plugin_definitions = $type->getDefinitions();

        foreach ($plugin_definitions as $plugin) {
          $plugins[$plugin['id']] = $type;
        }
      }
    }

    return new static(
      $container->get('eloqua.client_factory'),
      $container->get('request_stack'),
      $container->get('language_manager'),
      $container->get('entity_type.manager'),
      $container->get('queue'),
      $plugins
    );
  }

  /**
   * Instantiate and store the instance ID from Eloqua.
   *
   * @return \Drupal\eloqua_app_cloud\Controller\JsonResponse
   */
  public function instantiate($eloqua_app_cloud_service) {
    // Get the instanceID from the query parameter.
    $instanceId = $this->request->get("instance");
    if (empty($instanceId)) {
      //Throw an exception, and/or return an error?
    }
    $pluginReferences = $this->getEntityPlugins($eloqua_app_cloud_service);

    // Iterate over the ServiceEntity plugins and build a merged field list.
    foreach ($pluginReferences as $pluginReference) {
      $id = $pluginReference->value;
      // Get the plugin manager from the list of plugins (from the container).
      $pluginMgr = $this->plugins[$id];
      // Instantiate the referenced plugin.
      $plugin = $pluginMgr->createInstance($id);
      // We can only allow ONE type of API at a time.
      if(!empty($api) && $api !== $plugin->api()){
        // @TODO Throw an exception!
      }
      $api = $plugin->api();
      // @TODO figure out how to determine field list? This pulls it from annotation, but maybe it should be in config so it can be customized?
      $fieldList = $plugin->fieldList();
      $fieldList = array_merge($fieldList, $plugin->fieldList());
    }

    $response = new \stdClass();
    $response->recordDefinition = $fieldList;
    $response->requiresConfiguration = FALSE;
    \Drupal::logger('EloquaAppCloudDecision')->notice(print_r($response, TRUE));
    return new JsonResponse($response);
  }

  /**
   * @param $eloqua_app_cloud_service
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function update($eloqua_app_cloud_service) {
    // Get the instanceID from the query parameter.
    $instanceId = $this->request->get("instance");
    if (empty($instanceId)) {
      // @TODO Throw an exception, and/or return an error?
    }
    $pluginReferences = $this->getEntityPlugins($eloqua_app_cloud_service);

    // Iterate over the ServiceEntity plugins and build a merged field list.
    foreach ($pluginReferences as $pluginReference) {
      $id = $pluginReference->value;
      // Get the plugin manager from the list of plugins (from the container).
      $pluginMgr = $this->plugins[$id];
      // Instantiate the referenced plugin.
      $plugin = $pluginMgr->createInstance($id);
      // We can only allow ONE type of API at a time.
      if(!empty($api) && $api !== $plugin->api()){
        // Throw an exception!
      }
      $api = $plugin->api();
      // @TODO figure out how to determine field list? This pulls it from annotation, but maybe it should be in config so it can be customized?
      $fieldList = $plugin->fieldList();
      $fieldList = array_merge($fieldList, $plugin->fieldList());
    }

    $response = new \stdClass();
    $response->recordDefinition = $fieldList;
    $response->requiresConfiguration = FALSE;
    \Drupal::logger('EloquaAppCloudDecision')->notice(print_r($response, TRUE));
    return new JsonResponse($response);
  }

  /**
   * Delete.
   *
   * @return string
   *   Return Hello string.
   */
  public function delete($eloqua_app_cloud_service) {
  // Delete any existing queue entries for this service entity.

  }

  /**
   * Execute.
   *
   * @return string
   *   Return Hello string.
   */
  public function execute($eloqua_app_cloud_service) {
    \Drupal::logger('EloquaAppCloudDecision')->notice('Executing' );
    // Get the instanceID from the query parameter.
    $instanceId = $this->request->get("instance");
    // Get the execution ID
    $executionId = $this->request->get("executionId");
    if (empty($instanceId)) {
      // @TODO Throw an exception, and/or return an error?
      return new JsonResponse(['error no instanceId']);
    }
    //
    //$content = $this->mockContent();
    // Now load the JSON payload form Eloqua.
     $content = $this->request->getContent();

    if (empty($content)) {
      // @TODO Throw an exception, and/or return an error?
      return new JsonResponse(['error no content']);
    }
    $payload = json_decode($content);
    $records = $payload->items;

    $pluginReferences = $this->getEntityPlugins($eloqua_app_cloud_service);

    // Iterate over the ServiceEntity plugins and then over the payload items.
    foreach ($pluginReferences as $pluginReference) {
      $id = $pluginReference->value;
      // Get the plugin manager from the list of plugins (from the container).
      $pluginMgr = $this->plugins[$id];
      // Instantiate the referenced plugin.
      $plugin = $pluginMgr->createInstance($id);

      /**
       * Get the appropriate queue for this plugin.
       * @var QueueInterface $queue
       */
      $queue = $this->queueFactory->get($plugin->queueWorker());
        // Put the records directly onto on the queue.
      foreach ($records as $record) {
        // Put the result of the plugin on each record.
        $record->result = $plugin->execute($record);
      }
      // @TODO Define a queueItem class?
      $queueItem = new \stdClass();
      // Pass the queue type to the worker to make it easy to requeue if there are more then 5000 records.
      $queueItem->queueId = $plugin->queueWorker();
      // Pass the instance ID so the worker can communicate with Eloqua.
      $queueItem->instanceId = $instanceId;
      if(!empty($executionId)){
        $queueItem->executionId = $executionId;
      }
      $queueItem->api = $plugin->api();
      $queueItem->fieldList = $plugin->fieldList();
      $queueItem->records = $records;
      $queue->createItem($queueItem);
    }

    // If we have gotten through all that we just return a 204 to indicate an asynchronous response.
    $response = [];
    $response['statue'] = 204;
    return new JsonResponse($response);
  }

  private function getEntityPlugins($eloqua_app_cloud_service){
    //Get the service entity defined at this route.
    $entity = $this->entityManager->getStorage('eloqua_app_cloud_service')
      ->load($eloqua_app_cloud_service);
    // Return the list of plugin references.
    return $entity->field_eloqua_app_cloud_responder->getIterator();
  }

  private function mockContent(){
    $content = <<<'EOD'
{
    "offset" : 0,
    "limit" : 1000,
    "totalResults" : 2,
    "count" : 2,
    "hasMore" : false,
    "items" :
    [
       {
          "ContactID" : "1",
          "EmailAddress" : "fred@example.com",
          "field1" : "stuff",
          "field2" : "things",
          "field3" : "et cetera"
       },
       {
          "ContactID" : "2",
          "EmailAddress" : "john@example.com",
          "field1" : "more stuff",
          "field2" : "other things",
          "field3" : "and so on"
       }
    ]
}
EOD;
    return $content;
  }

}
