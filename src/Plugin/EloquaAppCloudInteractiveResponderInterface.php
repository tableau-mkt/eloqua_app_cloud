<?php

namespace Drupal\eloqua_app_cloud\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Eloqua AppCloud Interactive Responder plugins.
 */
interface EloquaAppCloudInteractiveResponderInterface extends PluginInspectionInterface {


  /**
   * Method gets called when a create call is sent by Eloqua.
   *
   * @return mixed
   */
  public function instantiate();

  /**
   * Method gets called when a configure (update) call is sent by Eloqua.
   *
   * @return mixed
   */
  public function update();

  /**
   * Method that gets executed when a Interactive Service is invoked from
   * within the Eloqua UI.
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

  /**
   * @return string
   *     The type of response this plugin requires (i.e. synchronous or
   *   asynchronous).
   */
  public function respond();

  /**
   * @return string
   *     The label of this plugin. Will be used as page title for configure
   *   calls from Eloqua
   */
  public function label();

  /**
   * @return string
   *     The description of this plugin. Will be used for configure calls from
   *   Eloqua
   */
  public function description();

  /**
   * @return string
   * Really a boolean flag but defined as a string since Eloqua seems to need
   *   lowercase "true" and "false".
   */
  public function requiresConfiguration();

}
