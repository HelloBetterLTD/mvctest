<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/23/16
 * Time: 8:33 PM
 * To change this template use File | Settings | File Templates.
 */

class NotFoundController extends ErrorController
{


	public function getHTTPCode()
	{
		return 404;
	}

	public function getErrorCode()
	{
		return 500;
	}

	public function getTitle(){
		return 'Page Not Found';
	}

	public function getContent(){
		return '<p>The page you requested cannot be found. Please check your URL</p>';
	}

} 