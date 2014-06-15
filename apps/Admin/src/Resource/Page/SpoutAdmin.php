<?php
/**
 * This file is part of the Mackstar.Spout package.
 * 
 * (c) Richard McIntyre <richard.mackstar@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mackstar\Spout\Admin\Resource\Page;

use BEAR\Resource\ResourceObject;

class SpoutAdmin extends ResourceObject {

    public function onGet() {
        return $this;
    }

} 