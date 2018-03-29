<?php

declare(strict_types=1);

namespace Cortex\Auth\DataTables\Adminarea;

use Cortex\Auth\Models\Guardian;
use Cortex\Foundation\DataTables\AbstractDataTable;

class GuardiansDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = Guardian::class;

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $link = config('cortex.foundation.route.locale_prefix')
            ? '"<a href=\""+routes.route(\'adminarea.guardians.edit\', {guardian: hashids.encode(full.id), locale: \''.$this->request->segment(1).'\'})+"\">"+data+"</a>"'
            : '"<a href=\""+routes.route(\'adminarea.guardians.edit\', {guardian: hashids.encode(full.id)})+"\">"+data+"</a>"';

        return [
            'username' => ['title' => trans('cortex/auth::common.username'), 'render' => $link.'+(full.is_active ? " <i class=\"text-success fa fa-check\"></i>" : " <i class=\"text-danger fa fa-close\"></i>")', 'responsivePriority' => 0],
            'email' => ['title' => trans('cortex/auth::common.email')],
            'created_at' => ['title' => trans('cortex/auth::common.created_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
            'updated_at' => ['title' => trans('cortex/auth::common.updated_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
        ];
    }
}
