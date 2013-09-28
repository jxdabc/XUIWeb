<?php
	/*
		authors: 
			milestone.jxd@gmail.com
	*/

	$target = isset($argv[1]) ? $argv[1] : 'all';

	// make sure we are working here. 
	$PWD = dirname(__FILE__);
	chdir($PWD);


	switch ($target) {
		default:
		case 'css':
			// build css. 
			echo "\n";
			echo "Build XUI.css \n";
			echo "==============================\n";

			$CSS_SOURCE_ROOT = PATH('source/css');
			$CSS_INTERMEDIA = PATH("$CSS_SOURCE_ROOT/_build.intermedia.less");

			$css_release_path_root = PATH('release/css');
			if (PHP_OS == 'WINNT') echo `rmdir /S /Q $css_release_path_root`;
			else echo `rm -r $css_release_path_root`;
			echo `mkdir $css_release_path_root`;

			$skins = glob(PATH("$CSS_SOURCE_ROOT/skins/*"));
			foreach ($skins as $skin_path)
			{
				$skin_name = pathinfo($skin_path, PATHINFO_FILENAME);
				if ($skin_name == 'default')
					continue;

				if (PHP_OS == 'WINNT') echo `del $CSS_INTERMEDIA`;
				else echo `rm $CSS_INTERMEDIA`;

				echo "Build skin $skin_name...\n";

				$bases = glob(PATH("$CSS_SOURCE_ROOT/base/*"));
				usort($bases, 'CMP_FILE');
				foreach ($bases as $base)
					cat ($base, $CSS_INTERMEDIA, "\r\n");

				$def_skin_csses = glob(PATH("$CSS_SOURCE_ROOT/skins/default/css/*"));
				usort($def_skin_csses, 'CMP_FILE');
				foreach ($def_skin_csses as $def_skin_css)
					cat ($def_skin_css, $CSS_INTERMEDIA, "\r\n");

				$controls = glob(PATH("$CSS_SOURCE_ROOT/controls/*"));
				// do we need this behavior ?
				// usort($controls, 'CMP_FILE');
				foreach ($controls as $control)
					cat ($control, $CSS_INTERMEDIA, "\r\n");

				$skin_csses = glob(PATH("$skin_path/css/*"));
				usort($skin_csses, 'CMP_FILE');
				foreach ($skin_csses as $skin_css)
					cat ($skin_css, $CSS_INTERMEDIA, "\r\n");
				

				$css_release_path = PATH("$css_release_path_root/$skin_name");
				echo `mkdir $css_release_path`;

				if (PHP_OS == 'WINNT') 
				{
					echo `xcopy /E /Y $CSS_SOURCE_ROOT\\skins\\default\\img $css_release_path\\img\\`;
					echo `xcopy /E /Y $CSS_SOURCE_ROOT\\skins\\$skin_name\\img $css_release_path\\img\\`;
				}
				else 
				{
					echo `cp -r $CSS_SOURCE_ROOT/skins/default/img $css_release_path/img`;
					echo `cp -r $CSS_SOURCE_ROOT/skins/skin_name/img $css_release_path/img`;
				} 

				
				$css_release_file_name = PATH("$css_release_path/XUI.css");
				$css_release_file_name_compressed = PATH("$css_release_path/XUI.min.css");

				`lessc $CSS_INTERMEDIA > $css_release_file_name`;
				`uglifycss --cute-comments $css_release_file_name > $css_release_file_name_compressed`;

				echo "\n";
			}
			if ($target == 'css') break;
		
		case 'js':
			// build XUI.js. 
			echo "\n";
			echo "Build XUI.js \n";
			echo "==============================\n";

			$JS_INTERMEDIA = PATH('source/js/_build.intermedia.js');

			$js_release_path_root = PATH('release/js');
			if (PHP_OS == 'WINNT') echo `rmdir /S /Q $js_release_path_root`;
			else echo `rm -r $js_release_path_root`;
			echo `mkdir $js_release_path_root`;

			if (PHP_OS == 'WINNT') echo `del $JS_INTERMEDIA`;
			else echo `rm $JS_INTERMEDIA`;
			
			$bases = glob(PATH('source/js/base/*'));
			usort($bases, 'CMP_FILE');
			foreach ($bases as $base)
				cat ($base, $JS_INTERMEDIA, "\r\n");

			$controls = glob(PATH('source/js/controls/*'));
			// do we need this behavior ?
			// usort($controls, 'CMP_FILE');
			foreach ($controls as $control)
				cat ($control, $JS_INTERMEDIA, "\r\n");

			$js_release_file_name = PATH("$js_release_path_root/XUI.js");
			$js_release_file_name_compressed = PATH("$js_release_path_root/XUI.min.js");

			if (PHP_OS == 'WINNT') echo `copy $JS_INTERMEDIA $js_release_file_name`;
			else echo `cp $JS_INTERMEDIA $js_release_file_name`;

			`uglifyjs --comments -- $js_release_file_name > $js_release_file_name_compressed`;
			echo "\n";

			echo "\n";
			$date = date(DATE_RFC822);
			echo "DONE.\n";
			echo "$date.\n";

			if ($target == 'js') break;
	}
	
	

	// Utils. 
	function cat($src, $dst, $delimiter = NULL)
	{
		if (PHP_OS == 'WINNT')
			`type $src >> $dst`;
		else
			`cat $src >> $dst`;

		if ($delimiter) 
		{
			$f = fopen($dst, 'ab');
			fwrite($f, $delimiter);
			fclose($f);
		}
	}

	function PATH($path)
	{
		if (PHP_OS == 'WINNT')
			return str_replace('/', '\\', $path);
		else
			return str_replace('\\', '/', $path);
	}

	function CMP_FILE($l, $r)
	{
		$l = pathinfo($l, PATHINFO_FILENAME);
		$r = pathinfo($r, PATHINFO_FILENAME);
		$l = explode('.', $l); $l = (int)$l[0];
		$r = explode('.', $r); $r = (int)$r[0];
		return $l - $r;
	}
?>