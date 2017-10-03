<?php

namespace Drupal\eloqua_app_cloud\Plugin\EloquaAppCloudDecisionResponder;

use Drupal\eloqua_app_cloud\Plugin\EloquaAppCloudDecisionResponderBase;
use Drupal\eloqua_rest_api\Factory\ClientFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @EloquaAppCloudDecisionResponder(
 *  id = "DecisionDebugResponder",
 *  label = @Translation("Decision Debug Responder"),
 *  api = "contacts",
 *  queueWorker = "eloqua_app_cloud_decision_queue_worker",
 *  fieldList = {
 *    "EmailAddress" = "{{Contact.Field(C_EmailAddress)}}"
 *   }
 * )
 */
class DebugResponder extends EloquaAppCloudDecisionResponderBase {

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
    $this->logger->debug('Received decision service hook with payload @record', [
      '@record' => print_r($record, TRUE),
    ]);
    if($record->EmailAddress == "jkopel@tableau.com"){
      $record->result = FALSE;
    }else {
      $record->result = TRUE;
    }
    return $record;
  }

}
