
# load the necessary libraries
library('ggplot2')
library('forecast')
library('tseries')

# set the output file

# load the dataset
daily_data = read.csv('arimaprices.csv', header=FALSE, stringsAsFactors=FALSE)

ma_data = read.csv('maprices.csv', header=FALSE, stringsAsFactors=FALSE)

arimafit <- auto.arima(daily_data)

fcast <- forecast(arimafit, h = 25)

jpeg(filename="D:\\wamp64\\www\\output.jpg", width = 1200, height = 900, units = "px")

plot(forecast(arimafit, h = 25))
lines(ma_data,col="red")
dev.off()
sink("arimaprediction.out", append=FALSE, split=FALSE)
fcast
# close the output file
sink()
# unload the libraries
detach("package:ggplot2")
detach("package:forecast")
detach("package:tseries")
