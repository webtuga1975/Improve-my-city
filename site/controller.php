<?php
/**
 * @version     1.0
 * @package     com_improvemycity
 * @copyright   Copyright (C) 2011 - 2012 URENIO Research Unit. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      URENIO Research Unit
 */
 
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class ImprovemycityController extends JController
{

	public function display($cachable = false, $urlparams = false)
	{
		
		$view = JRequest::getCmd('view', 'issues');
		JRequest::setVar('view', $view);
		$v = & $this->getView($view, 'html');
		$v->setModel($this->getModel($view), true); //the default model (true) :: $view is either issues or issue
		$v->setModel($this->getModel('discussions'));
		$v->display();

		return $this; 
	}
		
	function addIssue()
	{
		$view = JRequest::getCmd('view', 'addissue');
		JRequest::setVar('view', $view);
		
		$v = & $this->getView($view, 'html');
		$v->setModel($this->getModel($view));
		//$v->display();
		parent::display();
		
		return $this;
	}	
	
	
	/**
	* only called async from ajax	
	* function returns a list of all comments for the specific issueid or false if fail
	*/
	function addComment()
	{
		JRequest::checkToken('get') or jexit('Invalid Token');
		
		$user =& JFactory::getUser();
		
		if(!$user->guest)
		{
			//update comments
			$model = $this->getModel('discussions');
			$comments = $model->comment(JRequest::getVar('issue_id'), $user->id, JRequest::getVar('description')); 
			
			if($comments == false){
				$ret['msg'] = JText::_('COMMENT_ERROR');
				echo json_encode($ret);
				return;
			}
			
			$ret['msg'] = JText::_('COMMENT_ADDED');
			//$ret['comments'] = json_encode($comments);
			$ret['comments'] = $comments;
			
			echo json_encode($ret);
			return;	
		}
		else {
			//$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
			$ret['msg'] = JText::_('ONLY_LOGGED_COMMENT');
			echo json_encode($ret);
			
		}
	}

	/**
	* only called async from ajax	
	* function returns vote counter or -1 if fail
	*/
	function addVote()
	{
		JRequest::checkToken('get') or jexit('Invalid Token');
		
		$user =& JFactory::getUser();
		if(!$user->guest)
		{
			//update vote
			$model = $this->getModel('issue');
			if($model->getHasVoted() == 0){
				$votes = $model->vote(); 
				if($votes == -1){
					$ret['msg'] = JText::_('VOTE_ERROR');
					echo json_encode($ret);
				}
			
				$ret['msg'] = JText::_('VOTE_ADDED');
				$ret['votes'] = $votes;
				echo json_encode($ret);
			}
			else{
				$ret['msg'] = JText::_('ALREADY_VOTED');
				echo json_encode($ret);			
			}
		}
		else {
			//$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
			$ret['msg'] = JText::_('ONLY_LOGGED_VOTE');
			echo json_encode($ret);
		}
		//return 0;
	}
		
	function smartLogin()
	{
		$view = JRequest::getCmd('view', 'smartLogin');
		JRequest::setVar('view', $view);
		
		$v = & $this->getView($view, 'html');
		///$v->setModel($this->getModel($view)); //$view=addissue
		//$v->display();
		parent::display();
		
		return $this;
	}
	
	/**
	* only called async from ajax as format=raw from ajax
	*/	
	function getMarkersAsXML()
	{
		JRequest::checkToken('get') or jexit('Invalid Token');
		$v = & $this->getView('issues', 'raw');
		$v->setModel($this->getModel('issues'), true);
		$v->display(); 
	}

	/**
	* only called async from ajax as format=raw from ajax
	*/	
	function getMarkerAsXML()
	{
		//JRequest::checkToken() or jexit('Invalid Token'); //for write
		JRequest::checkToken('get') or jexit('Invalid Token');	//for read
		
		$v = & $this->getView('issue', 'raw');
		$v->setModel($this->getModel('issue'), true);
		$v->display();
	}	
}