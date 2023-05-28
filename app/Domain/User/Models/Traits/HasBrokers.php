<?php

namespace Domain\User\Models\Traits;

use App\Exceptions\UserNotInBrokerException;
use Domain\Broker\Models\Broker;
use Domain\User\Events\UserJoinedBroker;
use Domain\User\Events\UserLeftBroker;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait HasBrokers
{
    public function brokers()
    {
        return $this->belongsToMany(Broker::class);
    }

    public function currentBroker()
    {
        return $this->hasOne(Broker::class, 'id', 'current_broker_id');
    }

    public function ownedBrokers()
    {
        return $this->hasMany(Broker::class, 'owner_id');
    }

    /**
     * Boot the user model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the user model uses soft deletes.
     *
     * @return void|bool
     */
    public static function bootUserHasBrokers()
    {
        static::deleting(function (Model $user) {
            if (! method_exists(User::class, 'bootSoftDeletes')) {
                $user->brokers()->sync([]);
            }

            return true;
        });
    }

    /**
     * Returns if the user owns a broker.
     *
     * @return bool
     */
    public function isOwner()
    {
        return ($this->brokers()->where('owner_id', '=', $this->getKey())->first()) ? true : false;
    }

    /**
     * Wrapper method for "isOwner".
     *
     * @return bool
     */
    public function isBrokerOwner()
    {
        return $this->isOwner();
    }

    /**
     * @param $broker
     * @return mixed
     */
    protected function retrieveBrokerId($broker)
    {
        if (is_object($broker)) {
            $broker = $broker->getKey();
        }
        if (is_array($broker) && isset($broker['id'])) {
            $broker = $broker['id'];
        }

        return $broker;
    }

    /**
     * Returns if the user owns the given broker.
     *
     * @param mixed $broker
     * @return bool
     */
    public function isOwnerOfBroker($broker)
    {
        $broker_id = $this->retrieveBrokerId($broker);

        return ($this->brokers()
            ->where('owner_id', $this->getKey())
            ->where('broker_id', $broker_id)->first()
        ) ? true : false;
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $broker
     * @param array $pivotData
     * @return $this
     */
    public function attachBroker($broker, $pivotData = [])
    {
        $broker = $this->retrieveBrokerId($broker);
        /*
         * If the user has no current broker,
         * use the attached one
         */
        if (is_null($this->current_broker_id)) {
            $this->current_broker_id = $broker;
            $this->save();

            if ($this->relationLoaded('currentBroker')) {
                $this->load('currentBroker');
            }
        }

        // Reload relation
        $this->load('brokers');

        if (! $this->brokers->contains($broker)) {
            $this->brokers()->attach($broker, $pivotData);

            event(new UserJoinedBroker($this, $broker));

            if ($this->relationLoaded('brokers')) {
                $this->load('brokers');
            }
        }

        return $this;
    }

    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param mixed $broker
     * @return $this
     */
    public function detachBroker($broker)
    {
        $broker = $this->retrieveBrokerId($broker);
        $this->brokers()->detach($broker);

        event(new UserLeftBroker($this, $broker));

        if ($this->relationLoaded('brokers')) {
            $this->load('brokers');
        }

        /*
         * If the user has no more brokers,
         * unset the current_broker_id
         */
        if ($this->brokers()->count() === 0 || $this->current_broker_id === $broker) {
            $this->current_broker_id = null;
            $this->save();

            if ($this->relationLoaded('currentBroker')) {
                $this->load('currentBroker');
            }
        }

        return $this;
    }

    /**
     * Attach multiple brokers to a user.
     *
     * @param mixed $brokers
     * @return $this
     */
    public function attachBrokers($brokers)
    {
        foreach ($brokers as $broker) {
            $this->attachBroker($broker);
        }

        return $this;
    }

    /**
     * Detach multiple brokers from a user.
     *
     * @param mixed $brokers
     * @return $this
     */
    public function detachBrokers($brokers)
    {
        foreach ($brokers as $broker) {
            $this->detachBroker($broker);
        }

        return $this;
    }

    /**
     * Switch the current broker of the user.
     *
     * @param object|array|int $broker
     * @return $this
     * @throws ModelNotFoundException
     * @throws UserNotInBrokerException
     */
    public function switchBroker($broker)
    {
        if ($broker !== 0 && $broker !== null) {
            $broker = $this->retrieveBrokerId($broker);
            $brokerModel = Broker::class;
            $brokerObject = ( new $brokerModel() )->find($broker);

            if (! $brokerObject) {
                $exception = new ModelNotFoundException();
                $exception->setModel($brokerModel);

                throw $exception;
            }

            if (! $brokerObject->users->contains($this->getKey())) {
                $exception = new UserNotInBrokerException();
                $exception->setBroker($brokerObject->name);

                throw $exception;
            }
        }

        $this->current_broker_id = $broker;
        $this->save();

        if ($this->relationLoaded('currentBroker')) {
            $this->load('currentBroker');
        }

        return $this;
    }
}
