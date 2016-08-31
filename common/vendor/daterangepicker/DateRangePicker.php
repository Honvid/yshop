<?php
/**
 * @author Bogdan Burim <bgdn2007@ukr.net> 
 */

namespace common\vendor\daterangepicker;

use Yii;
use yii\base\Model;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\Widget as Widget;

class DateRangePicker extends Widget
{

	/**
	 * @var string $selector
	 */
	public $selector;

	/**
	 * @var string JS Callback for Daterange picker
	 */
	public $callback;
	/**
	 * @var array Options to be passed to daterange picker
	 */
	public $options = [
		        'ranges' => '',
		        'locale' => [
		            'applyLabel' => '确定',
		            'cancelLabel' => '取消',
		            'fromLabel' => 'From',
		            'toLabel' => 'To',
		            'customRangeLabel' => 'Custom Range'
		        ],
		        'format' => 'YYYY/MM/DD'
		    ];
	/**
	 * @var array the HTML attributes for the widget container.
	 */
	public $htmlOptions = [];
	public $moment = true;

	/**
	 * Initializes the widget.
	 * If you override this method, make sure you call the parent implementation first.
	 */
	public function init()
	{
		if (!isset($this->htmlOptions['id'])) {
			$this->htmlOptions['id'] = $this->getId();
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

		if ($this->moment) {
			DateRangePickerAsset::$extra_js[] = defined('YII_DEBUG') && YII_DEBUG ? 'moment.js' : 'moment.min.js';
		}

		if ($this->selector)
		{
			$this->registerJs($this->selector, $this->options, $this->callback);
		} else {
			$id = $this->htmlOptions['id'];
			echo Html::tag('input', '', $this->htmlOptions);
			$this->registerJs("#{$id}", $this->options, $this->callback);
		}
	}

	protected function registerJs($selector, $options, $callback) {
		$view = $this->getView();
		DateRangePickerAsset::register($view);
		$js   = [];
		$js[] = '$("' . $selector . '").daterangepicker(' . Json::encode($options) . ($callback ? ', ' . Json::encode($callback) : '') . ');';
		$view->registerJs(implode("\n", $js),View::POS_READY);

	}
}

