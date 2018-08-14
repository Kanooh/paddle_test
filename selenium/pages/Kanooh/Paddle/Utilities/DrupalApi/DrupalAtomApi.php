<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\DrupalApi\DrupalAtomApi.
 */

namespace Kanooh\Paddle\Utilities\DrupalApi;

use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;

/**
 * Utility class to perform actions on Scald atoms.
 */
class DrupalAtomApi extends DrupalApi
{
    /**
     * Deletes an atom.
     *
     * @param int $id
     *   The ID of the atom to delete.
     */
    public function deleteAtom($id)
    {
        $this->performAction('delete', $id);
    }

    /**
     * Deletes all atoms.
     */
    public function deleteAllAtoms()
    {
        $this->deleteAtom(-1);
    }

    /**
     * Creates a basic atom.
     *
     * @return int
     *   The ID of the atom that was created.
     */
    public function createAtom()
    {
        $response = $this->performAction('create');
        return (int) $response->responseText;
    }

    /**
     * Returns the number of atoms in the library.
     *
     * @param string $type
     *   The atom type for which we want the count. Supported types: "image",
     *   "file" and "video". Defaults to 'all';
     *
     * @return int
     *   The number of atoms in the library.
     */
    public function getAtomCount($type = 'all')
    {
        $response = $this->performAction('count', 0, $type);
        return (int) $response->responseText;
    }

    /**
     * Performs an action on the atom API.
     *
     * @param string $action
     *   Either 'count', 'create', 'delete' or 'edit'.
     * @param int $id
     *   Optional atom ID to perform the action on.
     * @param string $type
     *   The atom type for which the action is going to be performed. Supported
     *   types: "image", "file" and "video". Defaults to 'all';
     *
     * @throws \Exception
     *   Thrown when the action could not be performed.
     */
    protected function performAction($action, $id = 0, $type = 'all')
    {
        $request = new HttpRequest($this->webdriver);
        $request->setMethod(HttpRequest::GET);
        $request->setUrl($this->baseUrl . '/webdriver/atom/' . $id . '/' . $action . '/' . $type);
        $response = $request->send();

        if ($response->responseText == 'ERROR') {
            throw new \Exception('Atom action "' . $action . '" with ID "' . $id . '" could not be performed.');
        }

        return $response;
    }
}
