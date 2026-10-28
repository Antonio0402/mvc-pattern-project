{% extends 'base.mvc.php' %}
{% blocks title %}Transaction{% endblock %}
{% block head %}
<style>
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        table tr th,
        table tr td {
            padding: 5px;
            border: 1px #eee solid;
        }
    </style>
{% endblock %}
<body>
    {% if (! empty($currentTransaction)): %}
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Check #</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{% formatDate($currentTransaction['date']) %}</td>
                <td>{{ currentTransaction['checkNumber'] }}</td>
                <td>{{ currentTransaction['description'] }}</td>
                <td>
                    {% if ($currentTransaction['amount'] < 0): %}
                    <span style="color: red;">
                        {% formatDollarAmount($currentTransaction['amount']) %}
                    </span>
                    {% elseif ($currentTransaction['amount'] > 0): %}
                    <span style="color: green;">
                        {% formatDollarAmount($currentTransaction['amount']) %}
                    </span>
                    {% else: %}
                    {% formatDollarAmount($currentTransaction['amount']) %}
                    {% endif %}
                </td>
            </tr>
        </tbody>
    </table>
    {% else: %}
    <p>Transaction not found.</p>
    {% endif %}
</body>

</html>