<?php

namespace App\Repositories;

use App\Models\Replycomment;

class RepCommentRepository
{
    protected Replycomment $model;
    public function __construct(Replycomment $repcomment)
    {
        $this->model = $repcomment;
    }

    public function update(int $id, array $data): bool|int
    {
        return $this->model->query()->find($id)->update($data);
    }
}
