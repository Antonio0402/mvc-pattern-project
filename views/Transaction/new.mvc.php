{% extends 'base.mvc.php' %}
{% blocks title %}>New Transaction<{% endblock %}
{% block content %}
    <h1>New Transaction</h1>
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
    <form action="/transactions/insert" method="post">
    {% include "form.mvc.php"; %}
    </form>
{% endblock %}