<?php

namespace CodeFlix\Listeners;

use Prettus\Repository\Events\RepositoryEntityCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use CodeFlix\PayPal\WebProfileClient;
use CodeFlix\Models\PayPalWebProfile;
use CodeFlix\Contracts\Repositories\PayPalWebProfileRepository;

class CreatePayPalWebProfileListener
{

    private $webProfileClient;
    private $repository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(WebProfileClient $webProfileClient, PayPalWebProfileRepository $repository)
    {
        $this->webProfileClient = $webProfileClient;
        $this->repository = $repository;
    }

    /**
     * Handle the event.
     *
     * @param  RepositoryEntityCreated  $event
     * @return void
     */
    public function handle(RepositoryEntityCreated $event)
    {
        $model = $event->getModel();

        if(!($model instanceof PayPalWebProfile)) {
            return;
        }

        $payPalWebProfile = $this->webProfileClient->create($model);
        \Config::set('webprofile_created', true);
        $this->repository->update(['code' => $payPalWebProfile->getId()], $model->id);
    }
}
