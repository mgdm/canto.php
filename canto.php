<?php

/**
 * Implements the Canto.js API around Cairo instead of canvas.
 * Inspired by http://www.davidflanagan.com/2010/07/cantojs-an-impr.html
 * @package Canto
 */

class Canto {

	protected $context;
	protected $surface;

	public $r = 0;
	public $g = 0;
    public $b = 0;
	public $strokeWidth = 1;

    public $pattern = null;

    /**
     * Constructor
     * @param object c
     */
	public function __construct($c) {		
		if ($c instanceof CairoSurface) {
			$this->surface = $c;
			$this->context = new CairoContext($this->surface);
		} else if ($c instanceof CairoContext) {
			$this->context = $c;
            $this->surface = $this->context->getTarget();
		} else {
			throw new InvalidArgumentException("Canto::__construct expects either a CairoSurface or a CairoContext");
		}
	}
	
	public function newPath() {
		$this->context->newPath();
		return $this;
	}

	public function closePath() {
		$this->context->newPath();
		return $this;
	}

	public function moveTo($x, $y) {
		$this->context->moveTo($x, $y);
		return $this;
	}

	public function lineTo($x, $y) {
		$argc = func_num_args();
		
		if (($argc % 2) != 0) {
			throw new InvalidArgumentException("Canto::moveTo expects an even number of arguments");
		}

		$args = func_get_args();
		for ($i = 0; $i < $argc; $i += 2) {
			$this->context->lineTo($args[$i], $args[$i + 1]);
		}

		return $this;
	}

	public function stroke(array $params = null) {
		$this->context->stroke();
		return $this;
	}

    public function fill(array $params = null) {
        $this->context->fill();
        return $this;
    }

    public function toPng($filename = null) {
        if ($filename != null) {
            $this->surface->writeToPng($filename);
            return $this;
        } else {
            $temp = fopen('php://temp', 'rw');
            $this->surface->writeToPng($temp);
            $filesize = ftell($temp);
            rewind($temp);
            $pngData = fread($temp, $filesize);
            return $pngData;
        }
    }

    public function save() {
        $this->context->save();
        return $this;
    }

    public function restore() {
        $this->context->restore();
        return $this;
    }

    public function toDataUrl() {
        $data = base64_encode($this->toPng());
        return 'data:image/png;base64,' . $data;
    }
}
