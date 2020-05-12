<?php

namespace Codexshaper\WP\Models;

use Codexshaper\WP\Traits\QueryBuilderTrait;

class Attachment extends BaseModel
{
    use QueryBuilderTrait;

    protected $endpoint = 'products';
}
