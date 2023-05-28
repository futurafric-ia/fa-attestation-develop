<?php

namespace Domain\Request\Actions;

use Domain\Request\Models\Request;
use Domain\Request\Models\RequestDiscussion;

final class CommentRequestAction
{
    public function execute(Request $request, array $data): RequestDiscussion
    {
        return $request->discussions()->create(['notes' => $data['note'] ,'user_id' => $data['user_id']]);
    }
}
