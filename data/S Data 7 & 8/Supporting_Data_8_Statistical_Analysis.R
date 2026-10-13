---
  title: "Statistical Analysis"
output: html_document
date: '`r Sys.Date()`'
---
  ```{r setup, include=FALSE}
knitr::opts_chunk$set(
  echo = FALSE,
  message = FALSE,
  warning = FALSE
)
pacman::p_load(summarytools, knitr, kableExtra, dplyr, gt, gtsummary, ResourceSelection)
here::set_here()
```

```{r}
ds <- read.csv("data_lama.csv")
```

## Option 1: Clean dfSummary with custom styling
```{r}
ds %>%
  select(-Case..) %>%  # Remove case number if not needed
  tbl_summary(
    statistic = list(
      all_continuous() ~ "{mean} ({sd})",
      all_categorical() ~ "{n} ({p}%)"
    ),
    digits = all_continuous() ~ 1,
    label = list(
      Current.age ~ "Current Age",
      Family.History ~ "Family History",
      Eligibility.by.TA..A.B. ~ "Eligibility (TA A/B)",
      Age.of.Affected.Patient ~ "Age of Affected Patient",
      ER ~ "ER Status",
      PR ~ "PR Status"
    )
  ) %>%
  modify_header(label ~ "**Variable**", stat_0 ~ "**Summary**") %>%
  bold_labels() %>%
  as_gt() %>%
  gt::tab_header(
    title = "Dataset Summary Statistics",
    subtitle = paste("N =", nrow(ds), "observations")
  )
```

```{r}

pacman::p_load(tidyverse, caret, pROC, irr, gtsummary, gt, broom, corrplot,VIM )


# Load data
ds <- read.csv("data_lama.csv")

# Data preprocessing
ds_clean <- ds %>%
  select(-Case..) %>%  # Remove case number if not needed
  mutate(
    # Convert eligibility to binary (A=1, B=0) for analysis
    TA_binary = ifelse(Eligibility.by.TA..A.B. == "A", 1, 0),
    AI_R1_binary = ifelse(Eligibility.by.DSA..A.B.by.AI.report_1 == "A", 1, 0),
    AI_R2_binary = ifelse(Eligibility.by.DSA..A.B.by.AI.report_2 == "A", 1, 0),
    
    # Convert hormone receptor status to binary
    ER_binary = ifelse(ER == "Positive", 1, 0),
    PR_binary = ifelse(PR == "Positive", 1, 0),
    HER2_binary = ifelse(HER2 == "Positive", 1, 0),
    
    # Triple negative status
    Triple_negative = ifelse(ER == "Negative" & PR == "Negative" & HER2 == "Negative", 1, 0),
    
    # Age groups
    Age_50_or_less = ifelse(Current.age <= 50, 1, 0),
    Age_affected_50_or_less = ifelse(Age.of.Affected.Patient <= 50, 1, 0)
  )

```

```{r}
# ==============================================================================
# 1. AGREEMENT ANALYSIS BETWEEN TA AND AI (Runs 1 & 2)
# ==============================================================================

print("=== AGREEMENT ANALYSIS ===")

# Agreement between TA and AI Run 1
conf_matrix_AI1 <- table(ds_clean$TA_binary, ds_clean$AI_R1_binary)
print("Confusion Matrix: TA vs AI Run 1")
print(conf_matrix_AI1)

# Agreement between TA and AI Run 2
conf_matrix_AI2 <- table(ds_clean$TA_binary, ds_clean$AI_R2_binary)
print("Confusion Matrix: TA vs AI Run 2")
print(conf_matrix_AI2)

# Agreement between AI Run 1 and AI Run 2
conf_matrix_AI1_AI2 <- table(ds_clean$AI_R1_binary, ds_clean$AI_R2_binary)
print("Confusion Matrix: AI Run 1 vs AI Run 2")
print(conf_matrix_AI1_AI2)

# Calculate Kappa statistics
kappa_TA_AI1 <- kappa2(data.frame(ds_clean$TA_binary, ds_clean$AI_R1_binary))
kappa_TA_AI2 <- kappa2(data.frame(ds_clean$TA_binary, ds_clean$AI_R2_binary))
kappa_AI1_AI2 <- kappa2(data.frame(ds_clean$AI_R1_binary, ds_clean$AI_R2_binary))

print(paste("Kappa TA vs AI Run 1:", round(kappa_TA_AI1$value, 3)))
print(paste("Kappa TA vs AI Run 2:", round(kappa_TA_AI2$value, 3)))
print(paste("Kappa AI Run 1 vs AI Run 2:", round(kappa_AI1_AI2$value, 3)))

# Percent agreement
percent_agree_TA_AI1 <- sum(ds_clean$TA_binary == ds_clean$AI_R1_binary) / nrow(ds_clean) * 100
percent_agree_TA_AI2 <- sum(ds_clean$TA_binary == ds_clean$AI_R2_binary) / nrow(ds_clean) * 100
percent_agree_AI1_AI2 <- sum(ds_clean$AI_R1_binary == ds_clean$AI_R2_binary) / nrow(ds_clean) * 100

print(paste("Percent Agreement TA vs AI Run 1:", round(percent_agree_TA_AI1, 1), "%"))
print(paste("Percent Agreement TA vs AI Run 2:", round(percent_agree_TA_AI2, 1), "%"))
print(paste("Percent Agreement AI Run 1 vs AI Run 2:", round(percent_agree_AI1_AI2, 1), "%"))

```
```{r}

# ==============================================================================
# 2. DIAGNOSTIC ACCURACY ANALYSIS (TA as Gold Standard)
# ==============================================================================

print("\n=== DIAGNOSTIC ACCURACY ANALYSIS ===")

# Function to calculate diagnostic metrics
calculate_metrics <- function(gold_standard, test_result) {
  cm <- confusionMatrix(factor(test_result), factor(gold_standard), positive = "1")
  
  sensitivity <- cm$byClass["Sensitivity"]
  specificity <- cm$byClass["Specificity"]
  ppv <- cm$byClass["Pos Pred Value"]
  npv <- cm$byClass["Neg Pred Value"]
  accuracy <- cm$overall["Accuracy"]
  
  return(list(
    sensitivity = sensitivity,
    specificity = specificity,
    ppv = ppv,
    npv = npv,
    accuracy = accuracy,
    confusion_matrix = cm$table
  ))
}

# Calculate metrics for AI Run 1
metrics_AI1 <- calculate_metrics(ds_clean$TA_binary, ds_clean$AI_R1_binary)
print("AI Run 1 Diagnostic Metrics:")
print(paste("Sensitivity:", round(metrics_AI1$sensitivity, 3)))
print(paste("Specificity:", round(metrics_AI1$specificity, 3)))
print(paste("PPV:", round(metrics_AI1$ppv, 3)))
print(paste("NPV:", round(metrics_AI1$npv, 3)))
print(paste("Accuracy:", round(metrics_AI1$accuracy, 3)))

# Calculate metrics for AI Run 2
metrics_AI2 <- calculate_metrics(ds_clean$TA_binary, ds_clean$AI_R2_binary)
print("\nAI Run 2 Diagnostic Metrics:")
print(paste("Sensitivity:", round(metrics_AI2$sensitivity, 3)))
print(paste("Specificity:", round(metrics_AI2$specificity, 3)))
print(paste("PPV:", round(metrics_AI2$ppv, 3)))
print(paste("NPV:", round(metrics_AI2$npv, 3)))
print(paste("Accuracy:", round(metrics_AI2$accuracy, 3)))

# ROC Analysis
roc_AI1 <- roc(ds_clean$TA_binary, ds_clean$AI_R1_binary, quiet = TRUE)
roc_AI2 <- roc(ds_clean$TA_binary, ds_clean$AI_R2_binary, quiet = TRUE)

# Extract AUC values properly
auc_AI1 <- as.numeric(roc_AI1$auc)
auc_AI2 <- as.numeric(roc_AI2$auc)

print(paste("\nAUC AI Run 1:", round(auc_AI1, 3)))
print(paste("AUC AI Run 2:", round(auc_AI2, 3)))

# Plot ROC curves
par(mfrow = c(1, 2))
plot(roc_AI1, main = "ROC Curve - AI Run 1", 
     xlab = "1 - Specificity", ylab = "Sensitivity",
     col = "blue", lwd = 2)
text(0.6, 0.2, paste("AUC =", round(auc_AI1, 3)), col = "blue")

plot(roc_AI2, main = "ROC Curve - AI Run 2", 
     xlab = "1 - Specificity", ylab = "Sensitivity",
     col = "red", lwd = 2)
text(0.6, 0.2, paste("AUC =", round(auc_AI2, 3)), col = "red")

# Combined ROC plot
par(mfrow = c(1, 1))
plot(roc_AI1, main = "ROC Curves Comparison", 
     xlab = "1 - Specificity", ylab = "Sensitivity",
     col = "blue", lwd = 2)
plot(roc_AI2, add = TRUE, col = "red", lwd = 2)
legend("bottomright", legend = c(paste("AI Run 1 (AUC =", round(auc_AI1, 3), ")"),
                                 paste("AI Run 2 (AUC =", round(auc_AI2, 3), ")")),
       col = c("blue", "red"), lwd = 2)


```
```{r}

# ==============================================================================
# 3. MULTIVARIABLE MODELING OF TA ELIGIBILITY
# ==============================================================================

print("\n=== MULTIVARIABLE MODELING ===")

# Prepare data for modeling
modeling_data <- ds_clean %>%
  select(TA_binary, Current.age, Age.of.Affected.Patient, 
         ER_binary, PR_binary, HER2_binary, Triple_negative,
         Age_50_or_less, Age_affected_50_or_less) %>%
  na.omit()

# Check for missing data
print("Missing data summary:")
print(colSums(is.na(modeling_data)))

# Descriptive statistics by TA eligibility
print("\nDescriptive statistics by TA eligibility:")
modeling_data %>%
  mutate(TA_eligibility = ifelse(TA_binary == 1, "Eligible (A)", "Not Eligible (B)")) %>%
  select(-TA_binary) %>%
  tbl_summary(by = TA_eligibility) %>%
  add_p() %>%
  bold_p(t = 0.05)

# Univariable analysis
print("\nUnivariable logistic regression:")
variables <- c("Current.age", "Age.of.Affected.Patient", 
               "ER_binary", "PR_binary", "HER2_binary", "Triple_negative",
               "Age_50_or_less", "Age_affected_50_or_less")

univar_results <- data.frame(
  Variable = character(),
  OR = numeric(),
  CI_lower = numeric(),
  CI_upper = numeric(),
  p_value = numeric(),
  stringsAsFactors = FALSE
)

for (var in variables) {
  formula_str <- paste("TA_binary ~", var)
  model <- glm(as.formula(formula_str), data = modeling_data, family = binomial)
  
  # Extract coefficients
  coef_summary <- summary(model)$coefficients
  if (nrow(coef_summary) > 1) {  # Check if variable coefficient exists
    or <- exp(coef_summary[2, 1])
    ci <- exp(confint(model)[2, ])
    p_val <- coef_summary[2, 4]
    
    univar_results <- rbind(univar_results, data.frame(
      Variable = var,
      OR = or,
      CI_lower = ci[1],
      CI_upper = ci[2],
      p_value = p_val
    ))
  }
}

print(univar_results)

# Multivariable logistic regression
print("\nMultivariable logistic regression:")

# Model 1: Clinical characteristics
model1 <- glm(TA_binary ~ Current.age + ER_binary + PR_binary + HER2_binary, 
              data = modeling_data, family = binomial)

# Model 2: Age-based factors
model2 <- glm(TA_binary ~ Age_affected_50_or_less + Triple_negative, 
              data = modeling_data, family = binomial)

# Model 3: Full model
model3 <- glm(TA_binary ~ Current.age + Age.of.Affected.Patient + 
                ER_binary + PR_binary + HER2_binary + Triple_negative, 
              data = modeling_data, family = binomial)

# Model summaries
print("Model 1 (Clinical characteristics):")
print(tidy(model1, exponentiate = TRUE, conf.int = TRUE))

print("\nModel 2 (Age-based factors):")
print(tidy(model2, exponentiate = TRUE, conf.int = TRUE))

print("\nModel 3 (Full model):")
print(tidy(model3, exponentiate = TRUE, conf.int = TRUE))

# Model comparison
print("\nModel comparison (AIC):")
print(paste("Model 1 AIC:", round(AIC(model1), 2)))
print(paste("Model 2 AIC:", round(AIC(model2), 2)))
print(paste("Model 3 AIC:", round(AIC(model3), 2)))

# Hosmer-Lemeshow test for model fit
library(ResourceSelection)
hl_test1 <- hoslem.test(model1$y, fitted(model1))
hl_test2 <- hoslem.test(model2$y, fitted(model2))
hl_test3 <- hoslem.test(model3$y, fitted(model3))

print("\nHosmer-Lemeshow goodness of fit test:")
print(paste("Model 1 p-value:", round(hl_test1$p.value, 3)))
print(paste("Model 2 p-value:", round(hl_test2$p.value, 3)))
print(paste("Model 3 p-value:", round(hl_test3$p.value, 3)))

# ROC for the best model
roc_model <- roc(modeling_data$TA_binary, predict(model2, type = "response"), quiet = TRUE)
auc_model <- as.numeric(roc_model$auc)

plot(roc_model, main = "ROC Curve - Best Multivariable Model", 
     xlab = "1 - Specificity", ylab = "Sensitivity",
     col = "darkgreen", lwd = 2)
text(0.6, 0.2, paste("AUC =", round(auc_model, 3)), col = "darkgreen")

```

```{r}
# ==============================================================================
# SUMMARY TABLE
# ==============================================================================

# Create summary table
summary_table <- data.frame(
  Metric = c("Agreement TA vs AI1 (%)", "Agreement TA vs AI2 (%)", 
             "Agreement AI1 vs AI2 (%)", "Kappa TA vs AI1", "Kappa TA vs AI2",
             "Kappa AI1 vs AI2", "AI1 Sensitivity", "AI1 Specificity",
             "AI1 AUC", "AI2 Sensitivity", "AI2 Specificity", "AI2 AUC"),
  Value = c(
    round(percent_agree_TA_AI1, 1),
    round(percent_agree_TA_AI2, 1),
    round(percent_agree_AI1_AI2, 1),
    round(kappa_TA_AI1$value, 3),
    round(kappa_TA_AI2$value, 3),
    round(kappa_AI1_AI2$value, 3),
    round(metrics_AI1$sensitivity, 3),
    round(metrics_AI1$specificity, 3),
    round(auc_AI1, 3),
    round(metrics_AI2$sensitivity, 3),
    round(metrics_AI2$specificity, 3),
    round(auc_AI2, 3)
  )
)

print("\n=== SUMMARY TABLE ===")
print(summary_table)

# Save results
write.csv(summary_table, "analysis_summary.csv", row.names = FALSE)
write.csv(univar_results, "univariable_results.csv", row.names = FALSE)

print("\nAnalysis complete! Results saved to CSV files.")

```

