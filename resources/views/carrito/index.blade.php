<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Carrito</title>
	<style>
		button { margin-right: 4px; }
		table { width: 100%; border-collapse: collapse; }
		th, td { padding: 6px; }
	</style>
	<script>
		function getCsrf() { return document.querySelector('meta[name="csrf-token"]').getAttribute('content'); }
		function updateBadgeFromCart(cart) {
			var badge = document.getElementById('cart-badge');
			if (!badge) return;
			var count = (cart.products ? cart.products.length : 0) + (cart.services ? cart.services.length : 0);
			badge.textContent = count;
			if (count > 0) badge.classList.remove('opacity-0');
		}

		async function cartPost(url) {
			const res = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': getCsrf(), 'Accept':'application/json' } });
			if (!res.ok) throw new Error('Error HTTP ' + res.status);
			return res.json();
		}

		function updateTotals(cart) {
			var totalEl = document.getElementById('cart-total');
			if (totalEl) totalEl.textContent = cart.total;
		}

		function updateProductRowFromCart(cart, id) {
			var prod = (cart.products || []).find(p => (p.id||null) == id);
			var row = document.getElementById('row-product-'+id);
			if (!row) return;
			if (!prod) {
				row.remove();
				return;
			}
			var qtyEl = document.getElementById('qty-'+id);
			var subEl = document.getElementById('sub-'+id);
			if (qtyEl) qtyEl.textContent = (prod.quantity || 1);
			if (subEl) subEl.textContent = ((prod.price||0) * (prod.quantity||1));
		}

		async function cartIncrement(id) {
			try {
				const data = await cartPost('{{ url('carrito/producto') }}/'+id+'/incrementar');
				updateProductRowFromCart(data.cart, id);
				updateTotals(data.cart);
				updateBadgeFromCart(data.cart);
			} catch (e) { alert('No se pudo incrementar'); }
		}
		async function cartDecrement(id) {
			try {
				const data = await cartPost('{{ url('carrito/producto') }}/'+id+'/disminuir');
				updateProductRowFromCart(data.cart, id);
				updateTotals(data.cart);
				updateBadgeFromCart(data.cart);
			} catch (e) { alert('No se pudo disminuir'); }
		}
		async function cartRemoveProduct(id) {
			try {
				const data = await cartPost('{{ url('carrito/producto') }}/'+id+'/eliminar');
				updateProductRowFromCart(data.cart, id);
				updateTotals(data.cart);
				updateBadgeFromCart(data.cart);
			} catch (e) { alert('No se pudo eliminar producto'); }
		}
		async function cartRemoveService(id) {
			try {
				const data = await cartPost('{{ url('carrito/servicio') }}/'+id+'/eliminar');
				var row = document.getElementById('row-service-'+id);
				if (row) row.remove();
				updateTotals(data.cart);
				updateBadgeFromCart(data.cart);
			} catch (e) { alert('No se pudo eliminar servicio'); }
		}
		async function cartClear() {
			try {
				const data = await cartPost('{{ route('carrito.clear') }}');
				var prodBody = document.getElementById('products-body');
				var servBody = document.getElementById('services-body');
				if (prodBody) prodBody.innerHTML = '';
				if (servBody) servBody.innerHTML = '';
				updateTotals(data.cart || { total: 0 });
				updateBadgeFromCart(data.cart || { products: [], services: [] });
			} catch (e) { alert('No se pudo vaciar'); }
		}
	</script>
</head>
<body>
<h1>Carrito</h1>
<p><a href="{{ route('home') }}">Inicio</a></p>
@if(session('status'))<p>{{ session('status') }}</p>@endif
@php($cart = $cart ?? ['products'=>[], 'services'=>[], 'total'=>0])
<h2>Productos</h2>
@if(empty($cart['products']))<p>Sin productos.</p>@else
<table border="1" cellpadding="4"><thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th>Acciones</th></tr></thead><tbody id="products-body">
@foreach($cart['products'] as $p)
<tr id="row-product-{{ $p['id'] }}">
	<td>{{ $p['id'] }}</td>
	<td>{{ $p['name'] }}</td>
	<td>{{ $p['price'] }}</td>
	<td id="qty-{{ $p['id'] }}">{{ $p['quantity'] ?? 1 }}</td>
	<td id="sub-{{ $p['id'] }}">{{ ($p['price'] ?? 0) * ($p['quantity'] ?? 1) }}</td>
	<td>
		<button type="button" onclick="cartIncrement({{ $p['id'] }})">+</button>
		<button type="button" onclick="cartDecrement({{ $p['id'] }})">-</button>
		<button type="button" onclick="cartRemoveProduct({{ $p['id'] }})">Eliminar</button>
	</td>
</tr>
@endforeach
</tbody></table>
@endif
<h2>Servicios</h2>
@if(empty($cart['services']))<p>Sin servicios.</p>@else
<table border="1" cellpadding="4"><thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Fecha</th><th>Hora</th><th>Acciones</th></tr></thead><tbody id="services-body">
@foreach($cart['services'] as $s)
<tr id="row-service-{{ $s['id'] }}">
	<td>{{ $s['id'] }}</td>
	<td>{{ $s['name'] }}</td>
	<td>{{ $s['price'] }}</td>
	<td>{{ $s['scheduled_date'] ?? '-' }}</td>
	<td>{{ $s['scheduled_time'] ?? '-' }}</td>
	<td>
		<button type="button" onclick="cartRemoveService({{ $s['id'] }})">Eliminar</button>
	</td>
</tr>
@endforeach
</tbody></table>
@endif
<h2>Total: <span id="cart-total">{{ $cart['total'] }}</span></h2>
<button type="button" onclick="cartClear()">Vaciar Carrito</button>
@if($cart['total']>0)
<form method="POST" action="{{ route('carrito.checkout') }}">@csrf <button type="submit">Comprar</button></form>
@endif
</body>
</html>