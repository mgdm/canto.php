<?php
require('canto.php');

$s = new CairoImageSurface(CairoFormat::ARGB32, 300, 300);
$context = new CairoContext($s);

$data = Canto($context)
	->setSource(1, 1, 1)
	->paint()
	->moveTo(100, 100)
	->setSource(0, 0, 0)
	->lineTo(200, 200, 200, 250, 100, 250, 100, 100)
	->stroke()
	->setSource(1, 0, 0)
	->lineTo(100, 150, 150, 200)
	->stroke()
	->moveTo(20, 50)
	->selectFont("Helvetica")->setFontSize(48)
	->showText("Hello world")
	->toPng();
file_put_contents('output.png', $data);
