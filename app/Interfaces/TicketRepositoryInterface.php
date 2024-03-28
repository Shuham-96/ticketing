<?php

namespace App\Interfaces;

interface TicketRepositoryInterface
{
    public function index();
    public function active();
    public function completed();
    public function getById($id);
    public function store(array $data);
    public function update(array $data,$id);
    public function delete($id);
    public function priorities();
    public function categories();
    public function statuses();
}