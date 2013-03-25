<?php
function P($privilege = 0) {
	return 0xFFFF == (session('privilege') & 0xFFFF);
}