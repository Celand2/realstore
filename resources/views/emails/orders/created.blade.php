<!DOCTYPE html>
<html lang="fr">
<body>
	<h1>Commande confirmée</h1>
	<p>Bonjour {{ $order->user->name }},</p>
	<p>Votre commande <strong>#{{ $order->id }}</strong> a bien été créée.</p>
	<p><strong>Total :</strong> {{ number_format($order->total, 2) }} FC</p>
	<p>Nous vous tiendrons informé de son évolution.</p>
	<p>Merci,<br>{{ config('app.name') }}</p>
</body>
</html>
