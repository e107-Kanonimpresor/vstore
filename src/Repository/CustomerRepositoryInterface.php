<?php

namespace Vstore\Repository;

interface CustomerRepositoryInterface
{
	/** @return bool true when the user already has a customer record */
	public function exists($userId);

	public function update($userId, array $data);

	/** @return int|false id of the new record */
	public function insert(array $data);

	public function setRefCode($userId, $refCode);
}
