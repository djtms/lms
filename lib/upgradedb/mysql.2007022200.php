<?php

/*
 * LMS version 1.11-cvs
 *
 *  (C) Copyright 2001-2008 LMS Developers
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

$DB->BeginTrans();

$DB->Execute("ALTER TABLE rtmessages ADD INDEX ticketid (ticketid)");
$DB->Execute("
    CREATE TABLE rtnotes (
	id int(11) 	     NOT NULL auto_increment,
	ticketid int(11)     NOT NULL DEFAULT '0',
        userid int(11)       NOT NULL DEFAULT '0',
	body text            NOT NULL DEFAULT '',
	createtime int(11)   NOT NULL DEFAULT '0',
	PRIMARY KEY (id),
	INDEX ticketid (ticketid)
    ) TYPE=MyISAM;
");

$DB->Execute("UPDATE dbinfo SET keyvalue = ? WHERE keytype = ?", array('2007022200', 'dbversion'));

$DB->CommitTrans();

?>