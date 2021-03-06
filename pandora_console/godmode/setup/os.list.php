<?php 

// Pandora FMS - http://pandorafms.com
// ==================================================
// Copyright (c) 2005-2010 Artica Soluciones Tecnologicas
// Please see http://pandorafms.org for full contribution list

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation for version 2.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// Load global vars
global $config;

check_login ();

if (! check_acl ($config['id_user'], 0, "PM") && ! is_user_admin ($config['id_user'])) {
	db_pandora_audit("ACL Violation", "Trying to access Setup Management");
	require ("general/noaccess.php");
	return;
}

$table = new stdClass();

$table->width = '100%';
$table->class = 'databox data';

$table->head[0] = '';
$table->head[1] = __('ID');
$table->head[2] = __('Name');
$table->head[3] = __('Description');
$table->head[4] = '';
$table->align[0] = 'center';
$table->align[4] = 'center';
$table->size[0] = '20px';
$table->size[4] = '20px';

$osList = db_get_all_rows_in_table('tconfig_os');
if ($osList === false) {
	$osList = array();
}

$table->data = array();
foreach ($osList as $os) {
	$data = array();
	$data[] = ui_print_os_icon($os['id_os'], false, true);
	$data[] = $os['id_os'];
	if(is_metaconsole())
		$data[] = '<a href="index.php?sec=advanced&sec2=advanced/component_management&tab=os_manage&action=edit&tab2=builder&id_os=' . $os['id_os'] . '">' . io_safe_output($os['name']) . '</a>';
	else
		$data[] = '<a href="index.php?sec=gsetup&sec2=godmode/setup/os&action=edit&tab=builder&id_os=' . $os['id_os'] . '">' . io_safe_output($os['name']) . '</a>';
	$data[] = ui_print_truncate_text(io_safe_output($os['description']), 'description', true, true);
	if ($os['id_os'] > 16) {
		if(is_metaconsole())
			$data[] = '<a href="index.php?sec=advanced&sec2=advanced/component_management&tab=os_manage&action=delete&tab2=list&id_os=' . $os['id_os'] . '">' . html_print_image("images/cross.png", true) . '</a>';
		else
			$data[] = '<a href="index.php?sec=gsetup&sec2=godmode/setup/os&action=delete&tab=list&id_os=' . $os['id_os'] . '">' . html_print_image("images/cross.png", true) . '</a>';
	}
	else {
		//The original icons of pandora don't delete.
		$data[] = '';
	}
	
	$table->data[] = $data;
}

if (isset($data)) {
	html_print_table($table);
}
else {
	ui_print_info_message ( array('no_close'=>true, 'message'=>  __('There are no defined operating systems') ) );
}

if (is_metaconsole()) {
	echo '<form method="post" action="index.php?sec=advanced&sec2=advanced/component_management&tab=os_manage&tab2=builder">';
		echo "<div style='text-align:right;width:" . $table->width . "'>";	
			html_print_submit_button (__('Create OS'), '', false, 'class="sub next"');
		echo "</div>";
	echo '</form>';
}

?>
