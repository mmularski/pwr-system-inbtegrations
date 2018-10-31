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
                                <xsl:value-of select="summary/order/status" />
                            </text>
                        </strong>
                    </h2>
                    <br />
                    <xsl:for-each select="summary/customer">
                        <strong>
                            <h3>
                                <text>Delivery address:</text>
                            </h3>
                        </strong>
                        <br />
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
                            <strong>Street:</strong>
                            <xsl:value-of select="street" />
                            <xsl:value-of select="concat(' ', building)" />
                        </text>
                        <br />
                        <text>
                            <strong>City:</strong>
                            <xsl:value-of select="concat(' ', city)" />
                        </text>
                        <br />
                        <text>
                            <strong>Post Code:</strong>
                            <xsl:value-of select="concat(' ', postCode)" />
                        </text>
                        <br />
                        <br />
                    </xsl:for-each>
                    <table border="1">
                        <tr bgcolor="#1979c3" style="color:white">
                            <th style="text-align:left">SKU</th>
                            <th style="text-align:left">Name</th>
                            <th style="text-align:left">Count</th>
                            <th style="text-align:left">Price</th>
                        </tr>
                        <xsl:for-each select="summary/package/item">
                            <tr>
                                <td>
                                    <xsl:value-of select="sku" />
                                </td>
                                <td>
                                    <xsl:value-of select="name" />
                                </td>
                                <td>
                                    <xsl:value-of select="count" />
                                </td>
                                <td>
                                    <xsl:value-of select="price" />
                                </td>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>