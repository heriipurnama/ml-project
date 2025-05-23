# train_model.py
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense, Flatten
from tensorflow.keras.preprocessing.image import ImageDataGenerator

# Dummy model binary classification
model = Sequential([
    Flatten(input_shape=(64, 64, 3)),
    Dense(128, activation='relu'),
    Dense(1, activation='sigmoid')  # Binary classification: cat vs dog
])

model.compile(optimizer='adam', loss='binary_crossentropy', metrics=['accuracy'])

# Simulasi training tanpa data sebenarnya
model.save("model.h5")
print("Model saved as model.h5")
