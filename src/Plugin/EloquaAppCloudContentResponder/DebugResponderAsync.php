<?php

namespace Drupal\eloqua_app_cloud\Plugin\EloquaAppCloudContentResponder;

use Drupal\eloqua_app_cloud\Plugin\EloquaAppCloudContentResponderBase;
use Drupal\eloqua_rest_api\Factory\ClientFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @EloquaAppCloudContentResponder(
 *  id = "ContentDebugResponderAsync",
 *  label = @Translation("Asynchronous Content Debug Responder"),
 *  respond = "asynchronous",
 *  api = "contacts",
 *  queueWorker = "eloqua_app_cloud_content_queue_worker",
 *  fieldList = {
 *    "EmailAddress" = "{{Contact.Field(C_EmailAddress)}}"
 *   }
 * )
 */
class DebugResponderAsync extends EloquaAppCloudContentResponderBase {

  /**
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ClientFactory $eloqua, LoggerInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $eloqua);
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('eloqua.client_factory'),
      $container->get('logger.channel.eloqua_app_cloud')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function execute($record) {
    $this->logger->debug('Received content service hook with payload @record', [
      '@record' => print_r($record, TRUE),
    ]);
    $record->content = "<div>DEBUG CONTENT</div>";
    return $record;
  }
}
