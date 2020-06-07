from nsfw_detector import predict
model = predict.load_model('./model/nsfw_mobilenet2.224x224.h5')

# Predict single imaged
predict.classify(model, 'test2.jpg')
# {'2.jpg': {'sexy': 4.3454722e-05, 'neutral': 0.00026579265, 'porn': 0.0007733492, 'hentai': 0.14751932, 'drawings': 0.85139805}}

