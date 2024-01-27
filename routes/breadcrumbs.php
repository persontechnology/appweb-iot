<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Inicio', route('dashboard'));
});

// categoria-gateway
Breadcrumbs::for('categoria-gateway.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Categoría de gateway', route('categoria-gateway.index'));
});

Breadcrumbs::for('categoria-gateway.create', function (BreadcrumbTrail $trail) {
    $trail->parent('categoria-gateway.index');
    $trail->push('Nuevo', route('categoria-gateway.create'));
});
Breadcrumbs::for('categoria-gateway.edit', function (BreadcrumbTrail $trail,$cg) {
    $trail->parent('categoria-gateway.index');
    $trail->push('Editar', route('categoria-gateway.edit',$cg->id));
});

// categoria nodo
Breadcrumbs::for('categoria-nodo.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Categoría de nodo', route('categoria-nodo.index'));
});

Breadcrumbs::for('categoria-nodo.create', function (BreadcrumbTrail $trail) {
    $trail->parent('categoria-nodo.index');
    $trail->push('Nuevo', route('categoria-nodo.create'));
});
Breadcrumbs::for('categoria-nodo.edit', function (BreadcrumbTrail $trail,$cn) {
    $trail->parent('categoria-nodo.index');
    $trail->push('Editar', route('categoria-nodo.edit',$cn->id));
});

