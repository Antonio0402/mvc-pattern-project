
    <label for="date">Date</label>
    <input type="date" id="date" name="date" required value=<?php echo $transaction['date'] ?? "" ?>>

    <label for="checkNumber">Check Number</label>
    <input type="text" id="checkNumber" name="checkNumber" value=<?php echo $transaction['checkNumber'] ?? "" ?>>

    <label for="description">Description</label>
    <input type="text" id="description" name="description" required value=<?php echo $transaction['description'] ?? "" ?>>

    <label for="amount">Amount</label>
    <input type="number" id="amount" name="amount" step="0.01" required value=<?php echo $transaction['amount'] ?? "" ?>>

    <button type="submit"><?php echo $transaction['btnText'] ?> Transaction</button>
