<?php

/**
 * @author A.Jafaripur <mjafaripur@yahoo.com>
 * 
 * Components for yii2
 * 
 */

namespace common\components;

use Yii;
use yii\helpers\Html;
use yii\base\Component;
use yii\helpers\FileHelper;

class Helper extends Component {

	/**
	 * Check the given locale is rtl or not.
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param string $lang language locale
	 * @return boolean
	 */
	public function isRtl($lang = '') {
		$rtlLang = [
			'fa-IR',
		];
		if (trim($lang) == '') {
			$lang = \Yii::$app->language;
		}
		return in_array($lang, $rtlLang);
	}

	/**
	 * Create button for delete for yii gridview widget
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param string $url url for delete action
	 * @param string $permission permission for checking the user have an access to delete ir not.
	 * @param string $pjaxGridName Gridview name to update the gridview after deleting.
	 * @param string $messageContainer HTML container ID for show the message
	 * @return string generated link
	 */
	public function createDeleteButton($url, $permission = '', $pjaxGridName = '', $messageContainer = '') {
		if ($permission != '' && !Yii::$app->getUser()->can($permission))
			return '<i class="glyphicon glyphicon-trash"></i>';

		return Html::a('<i class="glyphicon glyphicon-trash"></i>', '#', [
				'title' => Yii::t('app', 'Delete'),
				"onclick" => "return deleteGridButton('{$url}', '" . Yii::t('app', 'Are you sure you want to delete this item?') . "', '{$pjaxGridName}', '{$messageContainer}' );"
		]);
	}

	/**
	 * Create button for updating item for yii gridview widget
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param string $url url for delete action
	 * @param string $permission permission for checking the user have an access to edit ir not
	 * @param boolean $useFancy show the given url result in fancy or not
	 * @param boolean $usePjax Using PJAX for editing or not
	 * @return string generated link
	 */
	public function createUpdateButton($url, $permission = '', $useFancy = false, $usePjax = false) {
		if ($permission != '' && !Yii::$app->getUser()->can($permission))
			return '<i class="glyphicon glyphicon-edit"></i>';

		return Html::a('<i class="glyphicon glyphicon-edit"></i>', $url, [
				'title' => Yii::t('app', 'Update'),
				'class' => $useFancy ? 'fancybox fancybox.ajax' : '',
				'data-pjax' => $usePjax ? '1' : '0',
		]);
	}

	/**
	 * get the google map API SDK
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @return string url of google map api to include in page.
	 */
	public function getGoogleMapUrl() {
		return 'https://maps.googleapis.com/maps/api/js?v=3.exp&language=' . $this->getTwoCharLanguage();
	}

	/**
	 * get the two chars of the locale
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com> 
	 * 
	 * @return string
	 */
	public function getTwoCharLanguage() {
		return substr(Yii::$app->language, 0, 2);
	}

	/**
	 * check the given name is suitable for file or not and remove unexpected chars from the file name.
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param string $file name of the file
	 * @return string
	 */
	public function safeFile($file) {
		// Remove any trailing dots, as those aren't ever valid file names.
		$file = rtrim($file, '.');

		$regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');

		return trim(preg_replace($regex, '', $file));
	}

	/**
	 * Normalize the string for adding in the url.
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param string $text
	 * @return string
	 */
	public static function normalizeTextForUrl($text){
		return preg_replace("#(\p{P}|\p{C}|\p{S}|\p{Z})+#u", "-", $text);
	}

	/**
	 * Format the latitude and longitude with fixed precision.
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param string $string
	 * @param integer $doubleCount
	 * @return float
	 */
	public function formatLatLng($string = '', $doubleCount = 10) {
		$ex = explode('.', $string);
		$ex[1] = substr($ex[1], 0, $doubleCount);
		$diff = $doubleCount - strlen($ex[1]);
		$zeros = substr('00000000', 0, $diff);
		$ex[1] .= $zeros;

		return implode('.', $ex);
	}
	
	/**
	 * Remove all directories in given path
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param string $path location path
	 */
	public function removeDirectories($path){
		$blacklist = array('.', '..');
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if (!in_array($file, $blacklist) && is_dir($path . DIRECTORY_SEPARATOR . $file)) {
					FileHelper::removeDirectory($path . DIRECTORY_SEPARATOR . $file);
				}
			}
			closedir($handle);
		}
	}
	
	/**
	 * Get list of the languages used in the website with label and url.
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @return array the key, value with label and url items
	 */
	public function getLanguages(){
		$route = Yii::$app->controller->route;
		$appLanguage = Yii::$app->language;
		$params = Yii::$app->getRequest()->get();
		
		array_unshift($params, $route);
		$items = [];
		foreach ($this->getLanguagesList() as $language) {
            if ($language === $appLanguage) {
                continue;  // Exclude the current language
            }
            $params['language'] = $language;
            $items[] = [
                'label' => $this->getLabelLanguage($language),
                'url' => $params,	
            ];
        }
		
		return $items;
	}
	
	/**
	 * Get list of language for show in adding or editing mode for selecting the language for content.
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @return array key, value array. key is the language code and value is label for that language
	 */
	public function getLanguageForMultiLingual(){
		$items['*'] = Yii::t('app', 'All');
		foreach ($this->getLanguagesList() as $language) {
            $items[$language] = $this->getLabelLanguage($language);
        }
		
		return $items;
	}
	
	/**
	 * Get list of languages
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @return array
	 */
	public function getLanguagesList(){
		return Yii::$app->urlManager->languages;
	}
	
	/**
	 * Get label for each language available in the website.
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param string $language language locale
	 * @return string
	 */
	private function getLabelLanguage($language){
		$labels = [
			'en' => Yii::t('app', 'English'),
			'en-GB' => Yii::t('app', 'English'),
			'en-US' => Yii::t('app', 'English'),
			'fa-IR' => Yii::t('app', 'Persian'),
		];

        return isset($labels[$language]) ? $labels[$language] : null;
	}
}
