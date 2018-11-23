<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <div class="block-content">
            <div class="box" style="width: 100% !important;">
                <div class="block-title">
                    <strong>Shipping details</strong>
                </div>
                <div class="box-content">
                    <h2>
                        <strong>
                            <text>Order status:
                                <xsl:value-of select="root/order/status" />
                            </text>
                        </strong>
                    </h2>
                    <br />
                    <xsl:for-each select="root/customer">
                        <text>
                            <strong>First Name:</strong>
                            <xsl:value-of select="concat(' ', firstName)" />
                        </text>
                        <br />
                        <text>
                            <strong>Last Name:</strong>
                            <xsl:value-of select="concat(' ', lastName)" />
                        </text>
                        <br />
                        <text>
                            <strong>E-mail:</strong>
                            <xsl:value-of select="concat(' ', email)" />
                        </text>
                        <br />
                        <br />
                        <br />
                    </xsl:for-each>
                    <h3>
                        <strong>
                            <text>Shipping method:
                                <xsl:value-of select="root/shipping" />
                            </text>
                        </strong>
                    </h3>
                    <br />
                    <table border="1">
                        <tr bgcolor="#1979c3" style="color:white">
                            <th style="text-align:left">Name</th>
                            <th style="text-align:left">Price</th>
                        </tr>
                        <tr>
                            <td>
                                Order subtotal
                            </td>
                            <td>
                                <xsl:value-of select="root/order_subtotal" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Shipping costs
                            </td>
                            <td>
                                <xsl:value-of select="root/shipping_cost" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Grand Total
                            </td>
                            <td>
                                <strong><xsl:value-of select="root/order_grandtotal" /></strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>