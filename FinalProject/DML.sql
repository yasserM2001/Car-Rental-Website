INSERT INTO offices (email, password, city, country, name)
VALUES
('office1@example.com', 'o1', 'CityA', 'CountryA', 'Office1'),
('office2@example.com', 'o2', 'CityB', 'CountryB', 'Office2'),
('office3@example.com', 'o3', 'CityC', 'CountryC', 'Office3'),
('office4@example.com', 'o4', 'CityD', 'CountryD', 'Office4');

INSERT INTO cars (model, plate_no, color, year, miles, price_per_day, date, office_id, image, status_id)
VALUES
('Toyota Camry', 'ABC1234', 'Blue', 2020, 20000, 50.00, '2023-01-01 10:00:00', 1, 'toyota_camry.jpg', 1),
('Honda Accord', 'XYZ5678', 'Red', 2019, 25000, 45.00, '2023-01-02 11:30:00', 2, 'honda_accord.jpg', 1),
('Ford Mustang', 'DEF4321', 'Black', 2022, 15000, 70.00, '2023-01-03 13:45:00', 1, 'ford_mustang.jpg', 1),
('Nissan Altima', 'JKL9876', 'White', 2022, 12000, 60.00, '2023-01-05 09:30:00', 3, 'nissan_altima.jpg', 1),
('BMW X5', 'PQR5432', 'Gray', 2021, 15000, 80.00, '2023-01-06 14:00:00', 2, 'bmw_x5.jpg', 1),
('Hyundai Sonata', 'GHI6543', 'Green', 2020, 18000, 55.00, '2023-01-07 16:45:00', 3, 'hyundai_sonata.jpg', 1),
('Mercedes-Benz C-Class', 'LMN7890', 'Silver', 2023, 8000, 90.00, '2023-01-08 18:30:00', 1, 'mercedes_c_class.jpg', 1);

INSERT INTO customers (fname, lname, email, password, address, phone, wallet)
VALUES
('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890', 500.00),
('Alice', 'Smith', 'alice.smith@example.com', 'securepass', '456 Oak St', '9876543210', 300.00),
('Bob', 'Johnson', 'bob.johnson@example.com', 'bobpass', '789 Pine St', '5551234567', 1000.00),
('Eva', 'Brown', 'eva.brown@example.com', 'evapass', '101 Elm St', '9998887777', 750.00);

INSERT INTO reservations (customer_id, car_id, startD, endD, cost, res_date)
VALUES
-- Car with car_id = 3 is rented from 2023-01-08 to 2023-01-18
(4, 3, '2023-01-08', '2023-01-18', 660.00, '2023-01-07 15:30:00'),
-- Car with car_id = 5 is rented from 2023-01-12 to 2023-01-22
(1, 5, '2023-01-12', '2023-01-22', 800.00, '2023-01-11 18:45:00'),
-- Car with car_id = 6 is rented from 2023-01-15 to 2023-01-25
(2, 6, '2023-01-15', '2023-01-25', 825.00, '2023-01-14 12:15:00'),
-- Car with car_id = 7 is rented from 2023-01-20 to 2023-01-30
(3, 7, '2023-01-20', '2023-01-30', 1350.00, '2023-01-19 09:00:00'),
-- Car with car_id = 1 is rented again from 2023-01-25 to 2023-02-05
(4, 1, '2023-01-25', '2023-02-05', 550.00, '2023-01-24 14:30:00'),
-- Car with car_id = 3 is rented again from 2023-01-28 to 2023-02-08
(1, 3, '2023-01-28', '2023-02-08', 720.00, '2023-01-27 16:00:00'),
-- Car with car_id = 5 is rented again from 2023-02-01 to 2023-02-11
(2, 5, '2023-02-01', '2023-02-11', 875.00, '2023-01-31 20:45:00'),
-- Car with car_id = 6 is rented again from 2023-02-05 to 2023-02-15
(3, 6, '2023-02-05', '2023-02-15', 950.00, '2023-02-04 10:30:00'),
-- Car with car_id = 7 is rented again from 2023-02-10 to 2023-02-20
(4, 7, '2023-02-10', '2023-02-20', 1200.00, '2023-02-09 22:15:00'),
-- Car with car_id = 1 is rented from 2023-01-01 to 2023-01-10
(1, 1, '2023-01-01', '2023-01-10', 500.00, '2023-01-01 09:00:00'),
-- Car with car_id = 2 is rented from 2023-01-03 to 2023-01-12
(2, 2, '2023-01-03', '2023-01-12', 405.00, '2023-01-02 12:30:00'),
-- Car with car_id = 4 is rented from 2023-01-06 to 2023-01-15
(3, 4, '2023-01-06', '2023-01-15', 900.00, '2023-01-05 11:30:00');
