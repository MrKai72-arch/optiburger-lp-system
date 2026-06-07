# 🍔 OptiBurger - Linear Programming Optimization System

## 👥 Group Members

| Name                              | Matrix Number |
| --------------------------------- | ------------- |
| [MUHAMMAD HARIZ MIRZA BIN HASSIM] | [2240240]     |
| [ADIB ARHAM BIN MARSUKI]          | [2240229]     |
| [AMIRA BINTI ASRI]                | [2240232]     |
| [NUR AINA ADDELINA BINTI AHMAD]   | [2240249]     |

## 📋 Project Description

OptiBurger is a web-based system that uses **Linear Programming** to help users choose the best combination of burgers within a budget to maximize taste satisfaction.

**Problem:** A student has RM15. Which burgers should they buy to get the most delicious experience?

## 📊 Linear Programming Formulation

### Decision Variables (0 = Don't buy, 1 = Buy)

| Variable | Burger         | Price  | Taste |
| -------- | -------------- | ------ | ----- |
| x₁       | Ramly Special  | RM6.00 | 9/10  |
| x₂       | Double Cheese  | RM5.00 | 8/10  |
| x₃       | Crispy Chicken | RM4.50 | 7/10  |
| x₄       | Veggie Burger  | RM4.00 | 5/10  |

### Objective Function (Maximize)

**Z = 9x₁ + 8x₂ + 7x₃ + 5x₄**

### Constraints

1. Budget: `6x₁ + 5x₂ + 4.5x₃ + 4x₄ ≤ 15`
2. Max burgers: `x₁ + x₂ + x₃ + x₄ ≤ 2`
3. Binary: `x₁, x₂, x₃, x₄ ∈ {0,1}`

### Optimal Solution

- Buy: **Ramly Special + Double Cheese**
- Total Cost: RM11.00
- Total Taste: 17/20
- Budget Remaining: RM4.00

## 🛠️ Technologies Used

- PHP 8.2
- MySQL 5.7
- HTML5/CSS3
- JavaScript (Dark/Light mode)
- XAMPP
- Git & GitHub

## 🚀 Installation Guide

### Prerequisites

- XAMPP installed
- Git (optional)

### Steps

1. **Clone or download** this repository to `C:\xampp\htdocs\OptiBurger`

2. **Start XAMPP** - Turn on Apache and MySQL

3. **Create database** - Open phpMyAdmin and create `optiburger_db`

4. **Import SQL** - Import `database.sql` into the database

5. **Generate password hash** - Visit `http://localhost/OptiBurger/hash_password.php` and copy the hash

6. **Insert user** - Run this in phpMyAdmin SQL:
   ```sql
   INSERT INTO users (username, password, full_name) VALUES
   ('student1', 'YOUR_HASH_HERE', 'Demo User');
   ```
