<?php

/*
 * LMS version 1.11-git
 *
 *  (C) Copyright 2001-2016 LMS Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  $Id$
 */




$layout['pagetitle'] = trans('Billing list');

$SESSION->save('backto', $_SERVER['QUERY_STRING']);

if(!isset($_GET['o']))
	$SESSION->restore('nlo', $o);
else
	$o = $_GET['o'];
$SESSION->save('nlo', $o);

if (isset($_GET['id'])) {
	$id = (int) $_GET['id'];
	$bill_list = $DB->GetAll('SELECT
										cdr.caller,
										cdr.callee,
										cdr.call_start_time,
										cdr.time_start_to_end,
										cdr.time_answer_to_end,
										cdr.voipaccountid,
										cdr.status,
										c.id as customerid,
										c.name,
										c.lastname,
										c.city,
										c.street,
										c.building
									FROM voip_cdr cdr left join customers c on cdr.voipaccountid = c.id WHERE voipaccountid = ?', array($id));
} else
	$bill_list = $DB->GetAll('SELECT
										cdr.caller,
										cdr.callee,
										cdr.call_start_time,
										cdr.time_start_to_end,
										cdr.time_answer_to_end,
										cdr.voipaccountid,
										cdr.status,
										c.id as customerid,
										c.name,
										c.lastname,
										c.city,
										c.street,
										c.building
									FROM voip_cdr cdr left join customers c on cdr.voipaccountid = c.id');


$voipaccountlist = $LMS->GetVoipAccountList($o, NULL, NULL);
$listdata['total'] = $voipaccountlist['total'];
$listdata['order'] = $voipaccountlist['order'];
$listdata['direction'] = $voipaccountlist['direction'];

unset($voipaccountlist['total']);
unset($voipaccountlist['order']);
unset($voipaccountlist['direction']);

if ($SESSION->is_set('valp') && !isset($_GET['page']))
	$SESSION->restore('valp', $_GET['page']);

$page = (!isset($_GET['page']) ? 1 : $_GET['page']);
$pagelimit = 30;
$start = ($page - 1) * $pagelimit;

$SESSION->save('valp', $page);

$hook_data = $plugin_manager->executeHook(
    'voipaccountlist_before_display',
    array(
        'voipaccountlist' => $voipaccountlist,
        'listdata' => $listdata,
        'smarty' => $SMARTY,
    )
);

$voipaccountlist = $hook_data['voipaccountlist'];
$listdata = $hook_data['listdata'];

$SMARTY->assign('billings', $bill_list);
$SMARTY->assign('total',count($bill_list));
$SMARTY->assign('page',$page);
$SMARTY->assign('pagelimit',$pagelimit);
$SMARTY->assign('start',$start);
$SMARTY->assign('voipaccountlist',$voipaccountlist);
$SMARTY->assign('listdata', $listdata);
$SMARTY->display('voipaccount/voipaccountbillinglist.html');

?>