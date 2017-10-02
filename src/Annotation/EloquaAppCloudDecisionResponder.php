<?php

namespace Drupal\eloqua_app_cloud\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Eloqua AppCloud Decision Responder item annotation object.
 *
 * @see \Drupal\eloqua_app_cloud\Plugin\EloquaAppCloudDecisionResponderManager
 * @see plugin_api
 *
 * @Annotation
 */
class EloquaAppCloudDecisionResponder extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The list of fields required by the plugin.
   *
   * @var array
   *
   */
  public $fieldList;

  /**
   * The API type (contacts or customObject).
   * @var string
   */
  public $api;


  /**
   * The name of the queue worker this plugin requires.
   * @var string
   */
  public $queueWorker;

}
