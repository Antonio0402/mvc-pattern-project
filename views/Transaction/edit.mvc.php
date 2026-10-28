{% extends 'base.mvc.php' %}
{% block title %}Edit Transaction{% endblock %}
{% block content %}
    <h1>Edit Transaction</h1>
    {% if (!empty($errors)): %}
        <ul>
            {% foreach ($errors as $field => $message): %}
                <li>
                    <strong><?= htmlspecialchars((string) $field, ENT_QUOTES, 'UTF-8') ?>:</strong>
                    <?= htmlspecialchars((string) $message, ENT_QUOTES, 'UTF-8') ?>
                </li>
            {% endforeach; %}
        </ul>
    {% endif; %}
        <form action="/transactions/{{ $transaction['checkNumber'] }}/update" method="post">
    {% include "form.php" %}
    </form>
    <a href="/transactions/{{ $itemSlug }}">Cancel</a
{% endblock %}