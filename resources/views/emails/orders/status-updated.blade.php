<!DOCTYPE html>
<html lang="fr">
<body>
	<h1>Statut de commande mis à jour</h1>
	<p>Bonjour {{ $order->user->name }},</p>
	<p>Le statut de votre commande <strong>#{{ $order->id }}</strong> est maintenant : <strong>{{ $order->status }}</strong>.</p>
	<p><strong>Total :</strong> {{ number_format($order->total, 2) }} FC</p>
	<p>Merci,<br>{{ config('app.name') }}</p>
</body>
</html>
