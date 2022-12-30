# Important Concepts

### Amount Precision

When specifying currency amounts using this library a `string` value will be interpreted as a 'scaled' value which will be 'descaled' when communicated to the Horizon API. This means that will be converted to a 64-bit integer representation by dividing it by ten million. If the value is specified as an `int`, or an `Int64`, the value will be interpreted as already 'descaled` and will not be further converted before being sent to Horizon.

More information here: https://developers.stellar.org/docs/fundamentals-and-concepts/stellar-data-structures/assets#relevance-in-horizon-and-stellar-client-libraries
