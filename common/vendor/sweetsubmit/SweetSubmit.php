<?php
/**
 * @author Bogdan Burim <bgdn2007@ukr.net> 
 *  $("#testone").click(function(){
 *     swal({
 *         "title": "test", 
 *         text: "error", 
 *         type: "error",  #"warning", "error", "success" and "info", input #
 *         imageUrl:null,
 *         imageSize:"80x80",
 *         html:false,
 *         timer:null,
 *         animation:true,  # pop, slide-from-top, slide-from-bottom #
 *         customClass:null,
 *         showCancelButton: true, 
 *         cancelButtonColor: "gray", 
 *         cancelButtonText: "取消",
 *         showConfirmButton:true,
 *         confirmButtonColor: "#DD6B55",
 *         confirmButtonText: "确定",
 *         closeOnConfirm: false,
 *         closeOnCancel: false, 
 *         allowOutsideClick:false,  # 点击空白处生效 #
 *         allowEscapeKey:true, # 按ESC键生效 #
 *         inputType:"text",
 *         inputPlaceholder:null,
 *         inputValue:null,
 *         showLoaderOnConfirm:false
 *     },function(isConfirm){
 *         if (isConfirm) {
 *             swal("Deleted!", "Your imaginary file has been deleted.", "success");
 *         } else {
 *             swal("Cancelled", "Your imaginary file is safe :)", "error");
 *         }
 *      });
 *  });
 *
 *  How To Use 
 *  <?php SweetSubmit::widget([
        'selector' => '#testone',
        'options' => [
            'title' => 'test',
            'text'  => 'error',
            'type' => 'error',
            'closeOnConfirm' => false,
            'closeOnCancel' => false,
        ],
        'function' => "
            function(isConfirm){
                if (isConfirm) {
                    swal(\"Deleted!\", \"Your imaginary file has been deleted.\", \"success\");
                } else {
                    swal(\"Cancelled\", \"Your imaginary file is safe :)\", \"error\");
                }
            }
        ",
    ]); ?>
 * 
 */

namespace common\vendor\sweetsubmit;

use Yii;
use yii\base\Model;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\Widget;

class SweetSubmit extends Widget
{
    /**
     * @var string $selector
     */
    public $selector;

    /**
     * @var string JS Callback for Daterange picker
     */
    public $function;
    /**
     * @var array Options to be passed to daterange picker
     */
    public $options = [];


    /**
     * @var array the HTML attributes for the widget container.
     */
    public $events = [];

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        if (!isset($this->selector)) {
            $this->selector = $this->getId();
        }
        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->registerPlugin();
    }

    protected function registerPlugin()
    {
        if ($this->selector)
        {
            $this->registerJs($this->selector, $this->options, $this->function);
        } else {
            $id = $this->options['id'];
            // echo Html::tag('input', '', $this->events);
            $this->registerJs("#{$id}", $this->options, $this->function);
        }
    }

    protected function registerJs($selector, $options, $function) {
        $view = $this->getView();

        SweetSubmitAsset::register($view);
        $options = Json::encode($options);
        $function = isset($function) ? ','.$function : '';
        $script = <<<SWEETSUBMIT
    $("{$selector}").click(function(){
        swal({$options}\n{$function});
    });
SWEETSUBMIT;
        $view->registerJs($script,View::POS_READY);
    }
}