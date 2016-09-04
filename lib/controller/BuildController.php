<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 4:43 PM
 * To change this template use File | Settings | File Templates.
 */

class BuildController extends Controller
{

	/**
	 * @return Page
	 */
	public function getDefaultRecord(){

		$page = new Page(array(
			'Title'			=> 'Building your system.',
			'MetaTitle'		=> 'Database and config rebuilding',
			'Content'		=> 'Your system is being built'
		));

		return $page;

	}


	/**
	 * @return string|void
	 */
	public function index(){
		$classes = ClassManifest::subclasses_for('Record');

		$str = '<p>Data base building</p>';
		$str.= '<ul>';
		foreach($classes as $class){
			if($class != 'Record'){
				$str.= Record::make_table($class);
			}
		}

		$str.= '</ul>';

		return $str;
	}


} 