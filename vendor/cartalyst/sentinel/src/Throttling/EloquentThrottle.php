<?php

/**
 * Part of the Sentinel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Sentinel
 * @version    2.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2015, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Sentinel\Throttling;

use Illuminate\Database\Eloquent\Model;

class EloquentThrottle extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'throttle';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'ip',
        'type',
    ];
}
