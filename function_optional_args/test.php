<?php
function my_append($new_item, $a_list=[]) {
	array_push($a_list, $new_item);
	return $a_list;
}

var_dump(my_append('one'));

var_dump(my_append('two'));
