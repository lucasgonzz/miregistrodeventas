<?php

function active($path){
	if(request()->is($path)){
		return 'nav-link-active';
	}
}