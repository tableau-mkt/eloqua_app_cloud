<?php

namespace Drupal\eloqua_app_cloud\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Eloqua AppCloud Action Responder plugins.
 */
interface EloquaAppCloudActionResponderInterface extends PluginInspectionInterface {

  /**
   * Method that gets executed when a Action Service is invoked from within the
   * Eloqua UI.
   *
   * @param object $record
   * object jsondecoded from Eloqua transmission.
   *
   * @return null
   */
  public function execute($record);

  /**
   * @return array
   *    The list of fields this plugin needs from Eloqua.
   */
  public function fieldList();
  /**
   * @return string
   *    The API type (contacts or customObject)
   */
  public function api();

  /**
   * @return string
   *     The name of the queue worker plugin this plugin requires.
   */
  public function queueWorker();

}
