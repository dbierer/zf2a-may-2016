<?php

namespace Status;

use PhlyRestfully\ResourceEvent;
use Zend\Db\TableGateway\TableGatewayInterface as TableGateway;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Math\Rand;
use Zend\Paginator\Adapter\DbTableGateway as TableGatewayPaginator;

class StatusListener extends AbstractListenerAggregate
{
    protected $statusTable;

    public function __construct(TableGateway $statusTable)
    {
        $this->statusTable = $statusTable;
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('create', array($this, 'onCreate'));
        $this->listeners[] = $events->attach('update', array($this, 'onUpdate'));
        $this->listeners[] = $events->attach('patch', array($this, 'onPatch'));
        $this->listeners[] = $events->attach('delete', array($this, 'onDelete'));
        $this->listeners[] = $events->attach('fetch', array($this, 'onFetch'));
        $this->listeners[] = $events->attach('fetchAll', array($this, 'onFetchAll'));
    }

    /**
     * Create a status
     *
     * This method demonstrates:
     *
     * - Retrieving parameters from the event
     * - Setting parameters back into the event
     * - Error handling (or lack thereof, as PhlyRestfully handles exceptions)
     *
     * @param  ResourceEvent $e
     * @return Status
     */
    public function onCreate(ResourceEvent $e)
    {
        $data = $e->getParam('data', array());
        $data = (array) $data;

        $id = Rand::getString(32, 'abcdef0123456789');
        $data['id'] = $id;

        $this->statusTable->insert($data);
        $e->setParam('id', $id);
        return $this->onFetch($e);
    }

    /**
     * Update an existing resource
     *
     * Your task:
     *
     * - Retrieve the data and id from the event
     * - Use the data and id to update the table
     * - Return the updated resource (use "$this->onFetch($e)")
     *
     * Patch is generally used for partial updates; since we only have one field
     * that may be updated, it is identical to replacing the status.
     *
     * @param  ResourceEvent $e
     * @return Status
     */
    public function onPatch(ResourceEvent $e)
    {
        $data = $e->getParam('data', array());
        $data = (array) $data;

        $this->statusTable->update($data, array('id' => $e->getParam('id')));
        return $this->onFetch($e);
    }

    /**
     * Update an existing resource
     *
     * Your task:
     *
     * - Proxy to onPatch() (i.e., nothing)
     *
     * Update is generally used for replacing a resource; since we only have
     * one field that may be updated, it is identical to patching the status.
     *
     * @param  ResourceEvent $e
     * @return Status
     */
    public function onUpdate(ResourceEvent $e)
    {
        return $this->onPatch($e);
    }

    /**
     * Delete a status
     *
     * Your task:
     *
     * - Retrieve the id from the event
     * - Use the id to delete the status from the table
     * - Indicate success/failure based on result of deletion
     *
     * @param  ResourceEvent $e
     * @return bool
     */
    public function onDelete(ResourceEvent $e)
    {
        $status = $this->statusTable->delete(array('id' => $e->getParam('id')));
        return ($status > 0);
    }

    /**
     * Fetch a status
     *
     * Your task:
     *
     * - Retrieve the id from the event
     * - Use the id to fetch a status from the table
     * - If the resultset is empty, raise an exception with code 404
     * - Otherwise, return the current() status from the resultset
     *
     * @param  ResourceEvent $e
     * @return Status
     * @throws \Exception
     */
    public function onFetch(ResourceEvent $e)
    {
        $id = $e->getParam('id');
        $resultSet = $this->statusTable->select(array('id' => $id));
        if (0 === $resultSet->count()) {
            throw new \Exception('Status not found', 404);
        }
        return $resultSet->current();
    }

    /**
     * Fetch all statuses
     *
     * Your task:
     *
     * - Create a TableGatewayPaginator instance with the composed table gateway
     * - Return a Statuses instance that composes the paginator adapter
     *
     * @param  ResourceEvent $e
     * @return Statuses
     */
    public function onFetchAll(ResourceEvent $e)
    {
        $adapter = new TableGatewayPaginator($this->statusTable);
        return new Statuses($adapter);
    }

}
