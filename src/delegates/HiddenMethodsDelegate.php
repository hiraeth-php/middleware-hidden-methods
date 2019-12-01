<?php

namespace Hiraeth\Middleware;

use Hiraeth;

/**
 * {@inheritDoc}
 */
class HiddenMethodsDelegate extends AbstractDelegate
{
	/**
	 * {@inheritDoc}
	 */
	static protected $defaultOptions = [
		'param' => '_action',
		'map'   => [
			'PUT'    => ['update'],
			'DELETE' => ['remove']
		]
	];


	/**
	 * {@inheritDoc}
	 */
	static public function getClass(): string
	{
		return HiddenMethods::class;
	}


	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		$options = $this->getOptions();

		return new HiddenMethods($options['param'], $options['map']);
	}
}
