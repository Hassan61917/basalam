<?php

namespace App\Console\Commands;

use App\Enums\PostStatus;
use App\ModelServices\Social\PostService;
use Illuminate\Console\Command;

class HandlePost extends Command
{
    public function __construct(
        private PostService $postService
    )
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handle:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $draftPosts = $this
            ->postService
            ->getDraftPosts()
            ->where("published_at", "<=", now())
            ->get();


        foreach ($draftPosts as $post) {
            $post->update(["status" => PostStatus::Published->value]);
        }

    }
}
