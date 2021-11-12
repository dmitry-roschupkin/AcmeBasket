<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <title>AcmeBasked demo</title>
</head>
<body>
    <form action="total.php" method="post">
        <p>
            Input prices:<br>
            <label>
                Input R01 price:
                <input type="text" name="priceR01" value="32.95"/>
            </label> <br>
            <label>
                Input G01 price:
                <input type="text" name="priceG01" value="24.95"/>
            </label> <br>
            <label>
                Input B01 price:
                <input type="text" name="priceB01" value="7.95"/>
            </label> <br>
        </p>
        <p>
            Input quantities:<br>
            <label>
                Input R01 quantity:
                <input type="text" name="countR01" value="0"/>
            </label> <br>
            <label>
                Input G01 quantity:
                <input type="text" name="countG01" value="1"/>
            </label> <br>
            <label>
                Input B01 quantity:
                <input type="text" name="countB01" value="1"/>
            </label> <br>
        </p>
        <p>
            Check special offers:<br>
            <label>
                <input type="checkbox" name="offerR01" value="1" checked/>
                Buy one red widget, get the second half price
            </label> <br>
            <label>
                <input type="checkbox" name="offerG01" value="1"/>
                Buy one green widget, get the second half price
            </label> <br>
            <label>
                <input type="checkbox" name="offerB01" value="1"/>
                Buy one blue widget, get the second half price
            </label> <br>
        </p>
        <p>
            Choose delivery charge rules:<br>
            <label>
                Rule 1
                <input type="text" name="amount1" value="0"/>
                <input type="text" name="cost1" value="4.95"/>
            </label> <br>
            <label>
                Rule 2
                <input type="text" name="amount2" value="50"/>
                <input type="text" name="cost2" value="2.95"/>
            </label> <br>
            <label>
                Rule 3
                <input type="text" name="amount3" value="90"/>
                <input type="text" name="cost3" value="0"/>
            </label> <br>
        </p>
        <p><input type="submit" /></p>
    </form>

    <?php
        /** @var $result */
        if (isset($result)) {
            echo "Result:<br>" . $result;
        }
    ?>

</body>
</html>